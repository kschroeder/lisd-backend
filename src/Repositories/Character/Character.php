<?php

namespace Lisd\Repositories\Character;

use Lisd\Repositories\AbstractDocument;

class Character extends AbstractDocument
{

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this['name'];
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this['name'] = $name;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this['description'];
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this['description'] = $description;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this['image'];
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this['image'] = $image;
    }



}
