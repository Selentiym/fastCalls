<?php
	interface iCall {
		/*
		These are the attributes that an iCall must have. Interfaces do not accept to declare members and 
		I am too lazy to create a getter/setter for each
		
		public $id_address;
		public $State;
		public $dateString;
		public $report;
		public $i;
		public $j;
		public $H;
		public $wishes;
		public $fio;
		public $birth;
		public $number;
		public $company;
		public $price;
		public $repair_type;
		public $comment;
		public $id_error;
		*/
		
		
		/**
		 * @return BaseCall | false - the model of the call corresonding to the object
		 * If the DB record is not found false is returned
		 */
		public function record();
		/**
		 * @return User - the owner of this call. The person who invited the patient.
		 */
		public function giveOwner();
		/**
		 * @arg object user - the user that may be the master of this call
		 * @return boolean - whether the user is or is not the master
		 */
		public function BelongsTo(User $user);
		/**
		 * @return integer - UNIX time of the call
		 */
		public function giveUnixTime();
		
	}
?>