<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as AppAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhoneNumberRepository")
 * @ORM\Embeddable()
 * @AppAssert\E164Number(groups={"E164"})
 * @Assert\GroupSequence({"PhoneNumber", "E164"})
 */
class PhoneNumber
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    // specify the length of generated verification codes
  const CODE_LENGTH = 6;
 
  /**
   * @ORM\Column(name="number", type="string", length=16, nullable=true)
   * @Assert\NotBlank()
   */
  protected $number;
 
  /**
   * @ORM\Column(name="country", type="string", length=2, nullable=true)
   * @Assert\NotBlank()
   */
  protected $country;
 
  /**
   * @ORM\Column(name="verification_code", type="string", length=PhoneNumber::CODE_LENGTH, nullable=true)
   */
  protected $verificationCode;
 
  /**
   * @ORM\Column(name="verified", type="boolean")
   */
  protected $verified = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
   * @param string $number
   * @return $this
   */
  public function setNumber($number)
  {
    $this->number = $number;
 
    return $this;
  }
 
  /**
   * @return string
   */
  public function getNumber()
  {
    return $this->number;
  }
 
  /**
   * @param string $country
   * @return $this
   */
  public function setCountry($country)
  {
    $this->country = $country;
 
    return $this;
  }
 
  /**
   * @return string
   */
  public function getCountry()
  {
    return $this->country;
  }
 
  /**
   * @return $this
   */
  public function setVerificationCode()
  {
    // generate a fixed-length verification code that's zero-padded, e.g. 007828, 936504, 150222
    $this->verificationCode = sprintf('%0'.self::CODE_LENGTH.'d', mt_rand(1, str_repeat(9, self::CODE_LENGTH)));
 
    return $this;
  }
 
  /**
   * @return string
   */
  public function getVerificationCode()
  {
    return $this->verificationCode;
  }
 
  /**
   * @param bool $verified
   * @return $this
   */
  public function setVerified($verified)
  {
    $this->verified = $verified;
 
    return $this;
  }
 
  /**
   * @return bool
   */
  public function isVerified()
  {
    return $this->verified;
  }
}
