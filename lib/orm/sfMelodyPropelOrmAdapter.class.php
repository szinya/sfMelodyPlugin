<?php
/**
 * Allow to override propel operation for melody specific usage
 *
 * @author Maxime Picaud
 * @since 29 août 2010
 */
class sfMelodyPropelOrmAdapter extends sfMelodyOrmAdapter
{
  public function findByMelody($melody)
  {
    $this->checkModels('sfGuardUser', 'findByMelody');

    $c = new Criteria();
    $q = Doctrine::getTable('sfGuardUser')
         ->createQuery('u')
         ->limit(1);

    $user_factory = $melody->getUserFactory();
    $config = $user_factory->getConfig();
    $user = $user_factory->getUser();
    $keys = $user_factory->getKeys();

   /*foreach($keys as $key)
    {

      $c->add()
      $method = 'get'.sfInflector::classify($key);
      if(is_callable(array($user, $method)))
      {
        $q->addWhere('u.'.$key.' = ?', $user->$method());
      }
    }*/

    return sfGuardUserPeer::doSelectOne($c);
  }
}