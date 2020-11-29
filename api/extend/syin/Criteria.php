<?php
namespace syin;

abstract class Criteria {
    public abstract function apply($model, Repository $repository);
}