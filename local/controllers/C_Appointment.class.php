<?php

class C_Appointment extends Controller {

	var $uiMode = 'normal';
	var $appointment = null;

	function actionAdd() {
		return $this->actionEdit();
	}
	
	function actionEdit() {
		if(is_null($this->appointment)) {
			$appointmentId = $this->getDefault('appointment_id',0);
			$apt =& Celini::newOrdo('Appointment',$appointmentId);
		} else {
			$apt =& $this->appointment;
		}
		$this->view->assign_by_ref('appointment',$apt);
		
		if($apt->get('id') > 0) {
			$this->view->assign('queue_id',$apt->get('queue_id'));
			$this->view->assign_by_ref('visitqueue',Celini::newORDO('VisitQueue',$apt->get('queue_id')));
			$this->view->assign('qreason_id',$apt->get('qreason_id'));
			$this->view->assign_by_ref('visitqueuereason',Celini::newORDO('VisitQueueReason',$apt->get('qreason_id')));
			$this->view->assign('provider_id',$apt->get('provider_id'));
			$this->view->assign_by_ref('provider',Celini::newORDO('Provider',$apt->get('provider_id')));
			$this->view->assign('patient_id',$apt->get('patient_id'));
			$this->view->assign_by_ref('patient',Celini::newORDO('Patient',$apt->get('patient_id')));
		}
		
		if($this->GET->get('queue_id') > 0) {
			$this->view->assign('queue_id',$this->GET->get('queue_id'));
			$this->view->assign('visitqueue',Celini::newORDO('VisitQueue',$this->GET->get('queue_id')));
			$this->view->assign('doappointmentpopup',true);
		}
		if($this->GET->get('qreason_id') > 0) {
			$this->view->assign('qreason_id',$this->GET->get('qreason_id'));
			$this->view->assign('visitqueuereason',Celini::newORDO('VisitQueueReason',$this->GET->get('qreason_id')));
			$this->view->assign('doappointmentpopup',true);
		}
		if($this->GET->get('provider_id') > 0) {
			$this->view->assign('provider_id',$this->GET->get('provider_id'));
			$this->view->assign('provider',Celini::newORDO('Provider',$this->GET->get('provider_id')));
			$this->view->assign('doappointmentpopup',true);
		}
		if($this->GET->get('patient_id') > 0) {
			$this->view->assign('patient_id',$this->GET->GET('patient_id'));
			$this->view->assign('patient',Celini::newORDO('Patient',$this->GET->get('patient_id')));
			$this->view->assign('doappointmentpopup',true);
		}
		if($this->GET->get('start_time') != '') {
			$this->view->assign('start_time',$this->GET->get('start_time'));
			$this->view->assign('end_time',$this->GET->get('end_time'));
			$this->view->assign('doappointmentpopup',true);
		}
		if($this->GET->get('date') != '') {
			$this->view->assign('apt_date',date('m/d/Y',strtotime($this->GET->get('date'))));
		}

		$head =& Celini::HTMLHeadInstance();
		$head->addNewJs('C_Appointment','templates/appointment/appointment.js');
		$head->addJs('scriptaculous');
		$head->addJs('clniPopup');
		$head->addExternalCss('suggest');
		$room =& $apt->getParent('Room');
		
		$this->view->assign_by_ref('room',$room);
		$this->view->assign('mode',$this->uiMode);

		
		return $this->view->render('edit.html');
	}

	function ajax_edit($id) {
		$apt =& Celini::newOrdo('Appointment',$id);
		$this->view->assign_by_ref('appointment',$apt);
		
		if($apt->get('id') > 0) {
			$this->view->assign('queue_id',$apt->get('queue_id'));
			$this->view->assign_by_ref('visitqueue',Celini::newORDO('VisitQueue',$apt->get('queue_id')));
			$this->view->assign('qreason_id',$apt->get('qreason_id'));
			$this->view->assign_by_ref('visitqueuereason',Celini::newORDO('VisitQueueReason',$apt->get('qreason_id')));
			$this->view->assign('provider_id',$apt->get('provider_id'));
			$this->view->assign_by_ref('provider',Celini::newORDO('Provider',$apt->get('provider_id')));
			$this->view->assign('patient_id',$apt->get('patient_id'));
			$this->view->assign_by_ref('patient',Celini::newORDO('Patient',$apt->get('patient_id')));
		}	
		$room =& $apt->getParent('Room');
		
		$this->view->assign_by_ref('room',$room);
		$this->view->assign('mode',$this->uiMode);
		$this->view->assign('ajaxedit',true);
		$this->view->assign('FORM_ACTION',Celini::link('day','CalendarDisplay'));
		return array($apt->get('id'),$this->view->render('edit.html'));
	}

	function process($data) {
		$db =& Celini::dbInstance();
//		$db->debug = true;
		$user =& $this->_me->get_user();
		$appointmentId = 0;
		if (isset($data['appointment_id'])) {
			$appointmentId = enforceType::int($data['appointment_id']);
		}
		$appointment =& Celini::newOrdo('Appointment',$appointmentId);
		$appointment->populateArray($data);
		if($appointment->get('id') > 0) {
			$appointment->set('last_change_date',date('Y-m-d'));
			$appointment->set('last_change_id',$user->get('id'));
		} else {
			// Check for consecutive no-shows
			$noshows = $appointment->checkNoShows();
			if($noshows > 2) {
				$this->messages->addMessage('Patient has not shown up for three consecutive appointments prior to this one.');
			}
			$appointment->set('created_date',date('Y-m-d'));
		}
		// Was this event in a schedule?
		$schedule =& Celini::newORDO('ScheduleEvent');
		$sfinder =& $schedule->relationshipFinder();
		$sfinder->addParent(Celini::newORDO('Provider',$appointment->get('provider_id')));
		$sfinder->addCriteria('event.start <= '.$db->quote($appointment->get('start_time')).' AND event.start > '.$db->quote($appointment->get('end_time')));
		$scheds = $sfinder->find();
		if($scheds->count() > 0) {
			$sched =& $scheds->current();
			$sch =& $sched->getParent('Schedule');
			$appointment->set('appointment_code',$sch->get('schedule_code'));
		}
		$appointment->persist();
		$this->appointment =& $appointment;
	}

	function ajax_cancel($id) {
		$apt =& Celini::newORDO('Appointment',$id);
		$apt->set('appointment_code','CAN');
		$apt->persist();
		return array($id,'<strong>CANCELLED</strong>');
	}
	
	function ajax_ns($id) {
		$apt =& Celini::newORDO('Appointment',$id);
		$apt->set('appointment_code','NS');
		$apt->persist();
		$noshows = $apt->checkNoShows(3,true);
		if(count($noshows) == 2) {
			$note =& Celini::newORDO('PatientNote');
			$note->set('user_id',$this->_me->get_user_id());
			$note->set('patient_id',$apt->get('patient_id'));
			$note->set('note_date',date('Y-m-d'));
			$note->set('note','Patient has three consecutive no-shows: '.implode(', ',$noshows));
			$note->persist();
		}
		$noshows = count($noshows);
		$noshows ++;
		return array($id,"<strong>NO SHOW #$noshows</strong>");
	}

	function ajax_delete($id) {
		$apt =& Celini::newORDO('Appointment',$id);
		$queue =& $apt->getParent('VisitQueue');
		$apt->drop();
		$qtext = '';
		if($queue->get('id') > 0) {
			$qtext .= '  This appointment was part of a queue.  Please <a href="'.Celini::link('Edit','VisitQueue').'queue_id='.$queue->get('id').'">reschedule</a>.';
		}
		return array($id,'<strong>DELETED</strong>'.$qtext);
	}
	
	/* Old stuff below needs to be updated */
	function editGroup_action_edit($appointment_id) {

		$oc =& ORDataobject::factory('Occurence',$appointment_id);
		$this->assign('start',$oc->get('start'));
		$this->assign('title',$oc->get('notes'));
		$this->assign('duration',$oc->get('duration'));
		$this->assign('FORM_ACTION',Celini::link(true,true,true,$appointment_id));
		$this->assign('ENCOUNTER_ACTION',Celini::link('Encounter','patient',true,0)."occurence_id=$appointment_id&");

		$go =& ORDataObject::Factory('GroupOccurence');
		$patientList = $go->getPatientlist($appointment_id);
		$patientListCount = count($patientList);

		$this->assign('patientList',$patientList);
		$this->assign('patientListCount',$patientListCount);

		return $this->view->render("editGroup.html");
	}

	function editGroup_action_process($appointment_id) {
		$go =& ORDataObject::factory('GroupOccurence');

		foreach($_POST['patient'] as $patient => $status) {
			if ($status) {
				$go->quickAdd($appointment_id,$patient);
			}
			else {
				$go->quickDrop($appointment_id,$patient);
			}
		}
		$oc =& ORDataobject::factory('Occurence',$appointment_id);
		if ($oc->get('group_appointment') != 1) {
			$oc->set('group_appointment',1);
			$oc->persist();
		}
	}

	function actionPullList() {

		$r =& Celini::newOrdo('Report','/Appointment/pullList','BySystemName');
		$lastPull = false;

		// were storing the last pull date using storage on the report, so if there isn't a pullList System report setup we can't store it
		// were storing a timestamp as an int, there is no storage for timestamps
		if ($r->isPopulated()) {
			$r->storage_metadata['text']['lastPull'] = true;

			$lastPull = $r->get('lastPull');
			if (!empty($lastPull)) {
				$lastPull = unserialize($lastPull);
			}
		}
		if (!$lastPull) {
			$lastPull = array();
		}

		// get upcoming appointments

		$start = date('Y-m-d H:i:s',strtotime('today 01:01:01'));
		$end = date('Y-m-d H:i:s',strtotime('today 23:59:59'));
		$sql = array();
		$sql['cols'] = "
			e.id event_id,
			pt.record_number,
			p.last_name,
			p.first_name,
			b.name building_name,
			concat(pro.last_name,', ',pro.first_name) provider,
			date_format(o.start,'%m/%d/%Y %H:%i') appointment_time
			";
		$sql['from'] = "
			occurences o
			inner join events e on o.event_id = e.id
			left join user u on o.user_id = u.user_id
			inner join patient pt on o.external_id = pt.person_id
			left join person p on pt.person_id = p.person_id
			left join person pro on u.person_id = pro.person_id
			left join rooms r on o.location_id = r.id
			left join buildings b on r.building_id = b.id
		";

		// build not in
		$notin = "";
		$today = date('Y-m-d');
		if (isset($lastPull[$today]) && count($lastPull[$today]) > 0) {
			$notin = " and e.id not in (".implode($lastPull[$today],',').') ';
		}
		$sql['where']  = "o.start between '$start' and '$end'$notin";

		$ds = new Datasource_sql();
		$ds->setup(Celini::dbInstance(),$sql,
			array(
				'record_number'=>'Record #',
				'last_name'=>'Last Name',
				'first_name'=>'First Name',
				'building_name'=>'Treating Facility',
				'provider'=>'Treating Provider',
				'appointment_time'=>'Appointment Time'
			));
		$grid =& new cGrid($ds);
		$this->view->assign_by_ref('grid',$grid);

		$store = $ds->toArray('event_id','event_id');

		if (!isset($lastPull[$today])) {
			$lastPull = array();
			$lastPull[$today] = $store;
		}
		else {
			$tmp = $lastPull[date('Y-m-d')];
			$lastPull = array();
			$lastPull[$today] = array_merge($tmp,$store);
		}
		$r->set('lastPull',serialize($lastPull));
		$r->persist();

		if (isset($this->noRender) && $this->noRender === true) {
			return "pullList.html";
		}
		return $this->view->render('pullList.html');
		
	}
	function actionView() {
		$apid = $this->GET->getTyped('appointment_id','int');
		$appointment =& Celini::newORDO('Appointment',$apid);
		$this->view->assign_by_ref('appointment',$appointment);
		$event =& $appointment->getParent('CalendarEvent');
		$this->view->assign_by_ref('event',$event);
		$provider =& $event->getParent('Provider');
		$patients =& $event->getChildren('Patient');
		$this->view->assign_by_ref('patients',$patients);
		$this->view->assign_by_ref('provider',$provider);
		$room =& $event->getParent('Room');
		$this->view->assign('ev_edit',true);
		$user =& User::fromPersonId($provider->get('id'));
		$this->view->assign_by_ref('provideruser',$user);
		$p =& $patients->current();
		$prsn =& Celini::newORDO('Person',$p->get('id'));
		$numbers = array_values($prsn->get_numbers());
		$this->view->assign('phone',isset($numbers[0]) ? $numbers[0]['number'] : '');
		return $this->view->render('singleappointment.html');
	}

	function getAJAXMethods(){
		return array('actionView', 'ajax_edit', 'ajax_cancel', 'ajax_ns', 'ajax_delete');
	}

	function actionSearch() {
		$head =& Celini::HTMLHeadInstance();
		$head->addExternalCss('calendar','calendar');
		if(!is_null($this->appointment)) {
			return $this->appointment;
		}
		$this->assign('search',$this->POST->getRaw('Search'));
		$providers =& Celini::newORDO('Provider');
		$providers = $providers->valueList('usernamePersonId');
		$this->assign('providers',$providers);
		$practices =& Celini::newORDO('Practice');
		$practices = $practices->practices_factory();
		$r =& Celini::newORDO('Room');
		
        if(count($practices) > 0) {
			$this->assign("facility",$r->rooms_practice_factory($practices[0]->get_id(),false));
        }

		return $this->view->render("search.html");
	}

	function processSearch() {
		$appointment =& Celini::newORDO('Appointment');
		$where = array();
		$search = $this->POST->getRaw('Search');
		// Assume today if no start date
		if(empty($search['from'])){
			$search['from']=date('Y-m-d');
		}
		// Assume a week from the start date if no end date
		if(empty($search['to'])){
			$search['to']=date('Y-m-d',strtotime('+1 Week',strtotime($search['from'])));
		}
		if (!isset($search['find_first'])) {
			// Regular search

			$db =& Celini::dbInstance();

			$where = array();
			if($search['provider'] != '') {
				$where[] = " prov.person_id =" .(int)$search['provider'];
			}
			if(!empty($search['facility'])) {
				$where[] = " rooms.id =" .(int)$search['facility'];
			}
			if(!empty($search['patient_id'])) {
				$where[] = " pat.person_id =" .(int)$search['patient_id'];
			}
			if(!empty($search['reason'])) {
				$where[] = " enumeration_value.value =" .(int)$search['reason'];
			}
			if(!empty($search['schedule_code'])) {
				$where[] = " event_group.title =" .$db->quote($search['schedule_code']);
			}
			
			$where[] = " event.start BETWEEN ".$db->quote($search['from'])." AND ".$db->quote($search['to']) ;
			$apts = '';
			$res =& $appointment->search('WHERE '.implode(' AND ',$where));
			while ($res && !$res->EOF) {
				$this->view->assign('apt',$res->fields);
				$apts.=$this->view->render('singlefromarray.html');
				$res->MoveNext();
			}
			$this->view->assign('searchresults',$apts);
			return;
		} else {
			if(strpos($search['time_amount'],':') !== false) {
				list($h,$m) = explode(':',$search['time_amount']);
				$amount=((int)$m+((int)$h*60))*60;
			} else {
				$amount = (int)$search['time_amount'] * 60;
			}
			$sched =& Celini::newORDO('Schedule');
			$ts = $sched->findFirst($search['provider'],$search['facility'],$search['from'],$search['to'],$amount,$search['schedule_code']);
			if(!$ts) {
				$this->messages->addMessage('No scheduled time in that amount found.');
			} else {
				$this->assign('ts',$ts);
				$this->assign('date',date('Y-m-d',$ts));
				$this->assign('start_time',date('H:i',$ts));
				$this->assign('end_time',date('H:i',$ts+$amount));
				$this->assign('usadate',date('m/d/Y',$ts));
			}
		}
	}
}
?>
