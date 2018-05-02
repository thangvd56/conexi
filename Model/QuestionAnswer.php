<?php

App::uses('AppModel', 'Model');

class QuestionAnswer extends AppModel {

    public $primary_key = 'id';
    public $name = 'QuestionAnswer';
    public $useTable = 'q_a_supports';
}
