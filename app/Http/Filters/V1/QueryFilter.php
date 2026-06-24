<?php

namespace App\Http\Filters\V1;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class QueryFilter{
    protected $builder;
    protected $request;

    // the request is passed here through dependecy injection
    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    public function filter($array){
        foreach($array as $key => $value){
            if(method_exists($this, $key)){
                $this->$key($value);
            }
        }

        return $this->builder;
    }

    // when the apply method is called it would inspect the query string in the url
    // and for each one it would find it would takes its value and call a function with
    // it's name such as: "?status=A" it would call a function called "status" and pass
    // "A" as the parameter "$this->status('A');"
    public function apply(Builder $builder){
        $this->builder = $builder;

        foreach($this->request->all() as $key => $value){
            if(method_exists($this, $key)){
                $this->$key($value);
            }
        }
    }
}