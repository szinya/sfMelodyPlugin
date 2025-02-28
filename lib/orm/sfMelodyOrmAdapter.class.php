<?php
class sfMelodyOrmAdapter
{
  const DOCTRINE = 'doctrine';
  const PROPEL = 'propel';

  protected $model;
  protected $orm;

  public function __construct($model)
  {
    $this->setModel($model);

    if(class_exists('Doctrine') || class_exists('Doctrine_Core'))
    {
      $this->orm = self::DOCTRINE;
    }
    else
    {
      $this->orm = self::PROPEL;
    }
  }

  public function setModel($model)
  {
    $this->model = $model;
  }

  public function getModel()
  {
    return $this->model;
  }

  public function getOrm()
  {
    return $this->orm;
  }

  public static function getInstance($model)
  {
    if(class_exists('Doctrine') || class_exists('Doctrine_Core'))
    {
      return new sfMelodyDoctrineOrmAdapter($model);
    }
    else
    {
      return new sfMelodyPropelOrmAdapter($model);
    }
  }

  public function __call($method, $arguments)
  {
    $callable = array($this->getAction(), $method);

    if(is_callable($callable))
    {
      return call_user_func_array($callable, $arguments);
    }
  }

  protected function getAction()
  {
    switch($this->getOrm())
    {
      case self::DOCTRINE:
        return call_user_func_array(array(class_exists('Doctrine') ? 'Doctrine' : 'Doctrine_Core', 'getTable'), array($this->getModel()));
      case self::PROPEL:
        return $this->getModel() . 'Peer';
    }
  }

  protected function checkModels($models, $method)
  {
    if((is_array($models) && !in_array($this->getModel(), $models)) || $models != $this->getModel())
    {
      throw new sfException(sprintf('"%s" doesn\'t have "%s" method', $this->getModel(), $method));
    }
    else return true;
  }
}
