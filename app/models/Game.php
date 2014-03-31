<?php

class Game extends Eloquent {
	protected $table = 'games';
	protected $guarded = array();
	public static $rules = array();

//    public function users()
//    {
//        return $this->hasManyThrough('Check', 'client_checks');
//    }
}
