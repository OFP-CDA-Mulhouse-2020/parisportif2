<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AddressRepository::class)
 */
class Address
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Assert\Type(type="integer",
     *  message="Id incorrect",
     *  groups={"address"}
     * )
     * @Assert\GreaterThan(
     *  value=0,
     *  message="Id Incorrect",
     * groups={"address"})
     */
    private int $id;


    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Adresse vide",
     *  groups={"address"}
     * )
     * @Assert\Regex(
     *  pattern ="/^[#.0-9a-zA-Z\s,-]+$/",
     *  message="Adresse : {{ value}} incorrect",
     *  groups={"addressNumberAndStreet", "address"}
     * )
     *
     */
    private string $addressNumberAndStreet;


    // /**
    //  * @ORM\Column(type="int", length=255)
    //  * @Assert\NotBlank(message="Code postal vide",
    //  *  groups={"address"}
    //  * )
    //  * @Assert\Regex(
    //  *  pattern ="/^[0-9]{5}$/",
    //  *  message="Code postal : {{ value}} incorrect",
    //  *  groups={"codeZip", "address"}
    //  * )
    //  *
    //  */
    private int $zipCode;


    // /**
    //  * @ORM\Column(type="string", length=255)
    //  * @Assert\NotBlank(message="Ville non renseignée",
    //  *  groups={"city", "address"}
    //  * )
    //  * @Assert\Regex(
    //  *  pattern ="/^[a-zA-ZÀ-ÿ '-]{1,30}$/",
    //  *  message="Ville : {{ value}} incorrect",
    //  *  groups={"address"}
    //  * )
    //  *
    //  */
    private string $city;


    // /**
    //  * @ORM\Column(type="string", length=255)
    //  * @Assert\NotBlank(message="Pays non renseignée",
    //  *  groups={"country", "address"}
    //  * )
    //  * @Assert\Regex(
    //  *  pattern ="/^[a-zA-ZÀ-ÿ '-]{1,30}$/",
    //  *  message="Pays : {{ value}} incorrect",
    //  *  groups={"address"}
    //  * )
    //  *
    //  */
    private string $country;





    /******************************** Id ****************************** */

    public function getId(): ?int
    {
        return $this->id;
    }


    /********************* Address Street Number ********************** */


    public function getAddressNumberAndStreet(): ?string
    {
        return $this->addressNumberAndStreet;
    }


    public function setAddressNumberAndStreet(string $addressNumberAndStreet): self
    {
        $this->addressNumberAndStreet = $addressNumberAndStreet;

        return $this;
    }


    /********************* zipCode ********************** */


    public function getZipCode(): ?int
    {
        return $this->zipCode;
    }


    public function setZipCode(int $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }



    /********************* Ville ********************** */


    public function getCity(): ?string
    {
        return $this->city;
    }


    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }


    /********************* France ********************** */


    public function getCountry(): ?string
    {
        return $this->country;
    }


    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }





    /**
     * Entity builder with requested parameters
     *
     * @return  self
     */
    public static function build(
        ?string $addressNumberAndStreet,
        ?int $zipCode,
        ?string $city,
        ?string $country
    ): Address {
        $address = new Address();
        $addressNumberAndStreet ? $address->setAddressNumberAndStreet($addressNumberAndStreet) : null;
        $zipCode ? $address->setZipCode($zipCode) : null;
        $city ? $address->setCity($city) : null;
        $country ? $address->setCountry($country) : null;

        return $address;
    }
}
