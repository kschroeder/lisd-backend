<?php

namespace Lisd\Repositories\Account;

use Lisd\Repositories\AbstractDocument;

class Account extends AbstractDocument
{

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this['user_id'];
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id)
    {
        $this['user_id'] = $user_id;
    }

    /**
     * @return mixed
     */
    public function getGivenName()
    {
        return $this['given_name'];
    }

    /**
     * @param mixed $given_name
     */
    public function setGivenName($given_name)
    {
        $this['given_name'] = $given_name;
    }

    /**
     * @return mixed
     */
    public function getFamilyName()
    {
        return $this['family_name'];
    }

    /**
     * @param mixed $family_name
     */
    public function setFamilyName($family_name)
    {
        $this['family_name'] = $family_name;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this['email'];
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this['email'] = $email;
    }

    /**
     * @return mixed
     */
    public function getPicture()
    {
        return $this['picture'];
    }

    /**
     * @param mixed $picture
     */
    public function setPicture($picture)
    {
        $this['picture'] = $picture;
    }

    /**
     * @return array
     */
    public function getDataArray()
    {
        return $this['data_array'];
    }

    /**
     * @param mixed $data
     */
    public function setDataArray(array $data)
    {
        $this['data_array'] = $data;
    }

}
