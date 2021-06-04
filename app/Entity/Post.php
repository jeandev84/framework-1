<?php
namespace App\Entity;


/**
 * Class Post
 * @package App\Entity
*/
class Post
{
     /**
      * @var int
     */
     private $id;


     /**
      * @var string
     */
     private $title;


     /**
      * @var string
     */
     private $content;


     /**
      * @var bool
     */
     private $published;



     /**
      * Post constructor.
     */
     public function __construct($id, $title, $content, $published = false)
     {
         $this->id = $id;
         $this->title = $title;
         $this->content = $content;
         $this->published = $published;
     }


     /**
       * @return int
     */
     public function getId(): int
     {
          return $this->id;
     }


    /**
     * @return string|null
    */
    public function getTitle(): ?string
    {
        return $this->title;
    }



    /**
     * @param string|null $title
     * @return Post
    */
    public function setTitle(?string $title): Post
    {
        $this->title = $title;

        return $this;
    }



    /**
     * @return string|null
    */
    public function getContent(): ?string
    {
        return $this->content;
    }



    /**
     * @param string|null $content
     * @return Post
   */
    public function setContent(?string $content): Post
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @param bool $published
     * @return $this
    */
    public function setPublished(bool $published): Post
    {
        $this->published = $published;

        return $this;
    }


    /**
     * @return bool
    */
    public function getPublished(): bool
    {
        return $this->published;
    }
}