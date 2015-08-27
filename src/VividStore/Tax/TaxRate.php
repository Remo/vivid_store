<?php
namespace Concrete\Package\VividStore\Src\VividStore\Tax;

use Package;
use Core;
use Database;

use \Concrete\Package\VividStore\Src\VividStore\Cart\Cart as VividCart;
use \Concrete\Package\VividStore\Src\VividStore\Customer\Customer;

defined('C5_EXECUTE') or die(_("Access Denied."));

/**
 * @Entity
 * @Table(name="VividStoreTaxRates")
 */
class TaxRate
{
    
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $trID;
    
    /**
     * @Column(type="boolean")
     */
    protected $taxEnabled;
    
    /**
     * @Column(type="string")
     */
    protected $taxLabel;
    
    /**
     * @Column(type="decimal")
     */
    protected $taxRate;
    
    /**
     * @Column(type="string")
     */
    protected $taxIncluded;
    
    /**
     * @Column(type="string")
     */
    protected $taxBasedOn;
    
    /**
     * @Column(type="string")
     */
    protected $addOrExtract;
    
    /**
     * @Column(type="string")
     */
    protected $taxAddress;
    
    /**
     * @Column(type="string")
     */
    protected $taxCountry;
    
    /**
     * @Column(type="string")
     */
    protected $taxState;
    
    /**
     * @Column(type="string")
     */
    protected $taxCity;
    
    public function setEnabled($enabled){ $this->taxEnabled = $enabled; }
    public function setTaxLabel($label){ $this->taxLabel = $label; }
    public function setTaxRate($rate){ $this->taxRate = $rate; }
    public function setTaxIncluded($included){ $this->taxIncluded = $included; }
    public function setTaxBasedOn($basedOn){ $this->taxBasedOn = $basedOn; }
    public function setAddOrExtract($addOrExtract){ $this->addOrExtract = $addOrExtract; }
    public function setTaxAddress($address){ $this->taxAddress = $address; }
    public function setTaxCountry($country){ $this->taxCountry = $country; }
    public function setTaxState($state){ $this->taxState = $state; }
    public function setTaxCity($city){ $this->taxCity = $city; }
    
    public function getTaxRateID(){ return $this->trID; }
    public function isEnabled(){ return $this->taxEnabled; }
    public function getTaxLabel(){ return $this->taxLabel; }
    public function getTaxRate(){ return $this->taxRate; }
    public function getTaxIncluded(){ return $this->taxIncluded; }
    public function getTaxBasedOn(){ return $this->taxBasedOn; }
    public function getAddOrExtract(){ return $this->addOrExtract; }
    public function getTaxAddress(){ return $this->taxAddress; }
    public function getTaxCountry(){ return $this->taxCountry; }
    public function getTaxState(){ return $this->taxState; }
    public function getTaxCity(){ return $this->taxCity; }
    
    public static function getByID($trID) {
        $db = Database::get();
        $em = $db->getEntityManager();
        return $em->find('Concrete\Package\VividStore\Src\VividStore\Tax\TaxRate', $trID);
    }
    
    public function isTaxable()
    {
        $taxAddress = $this->getTaxAddresss;
        $taxCountry = strtolower($this->getTaxCountry());
        $taxState = strtolower(trim($this->getTaxState()));
        $taxCity = strtolower(trim($this->getTaxCity()));
        
        $customer = new Customer;
        $customerIsTaxable = false;

        switch($taxAddress){
            case "billing":
                $userCity = strtolower(trim($customer->getValue("billing_address")->city));
                $userState = strtolower(trim($customer->getValue("billing_address")->state_province));
                $userCountry = strtolower(trim($customer->getValue("billing_address")->country));
                break;
            case "shipping":
                $userCity = strtolower(trim($customer->getValue("shipping_address")->city));
                $userState = strtolower(trim($customer->getValue("shipping_address")->state_province));
                $userCountry = strtolower(trim($customer->getValue("shipping_address")->country));
                break;
        }

        if ($userCountry == $taxCountry ) {
            $customerIsTaxable = true;
            if ($userState != $taxState) {
                $customerIsTaxable = false;
            } elseif ($userCity != $taxCity) {
                $customerIsTaxable = false;
            }
        }

        return $customerIsTaxable;
    }
    
    public function calculate()
    {
        $cart = VividCart::getCart();
        $taxtotal = 0;
        if($cart){
            foreach ($cart as $cartItem){
                $pID = $cartItem['product']['pID'];
                $qty = $cartItem['product']['qty'];
                $product = VividProduct::getByID($pID);
                if(is_object($product)){
                    if($product->isTaxable()){
                        $taxCalc = Config::get('vividstore.calculation');

                        if ($taxCalc == 'extract') {
                            $taxrate =  10 / (Config::get('vividstore.taxrate') + 100);
                        }  else {
                            $taxrate = Config::get('vividstore.taxrate') / 100;
                        }

                        switch(Config::get('vividstore.taxBased')){
                                case "subtotal":
                                    $productSubTotal = $product->getProductPrice() * $qty;
                                    $tax = $taxrate * $productSubTotal;
                                    $taxtotal = $taxtotal + $tax;
                                    break;
                                case "grandtotal":
                                    $productSubTotal = $product->getProductPrice() * $qty;
                                    $shippingTotal = Price::getFloat(self::getShippingTotal());
                                    $taxableTotal = $productSubTotal + $shippingTotal;
                                    $tax = $taxrate * $taxableTotal;
                                    $taxtotal = $taxtotal + $tax;
                                    break;
                            }

                    }//if product is taxable
                }//if obj
            }//foreach
        }//if cart
    }
    public static function add($data)
    {
        if($data['taxRateID']){
            $tr = self::getByID($data['taxRateID']);
        } else {
            $tr = new self();
        }
        $tr->setEnabled($data['taxEnabled']);
        $tr->setTaxLabel($data['taxLabel']);
        $tr->setTaxRate($data['taxRate']);
        $tr->setTaxIncluded($data['taxIncluded']);
        $tr->setTaxBasedOn($data['taxBased']);
        $tr->setAddOrExtract($data['addOrExtract']);
        $tr->setTaxAddress($data['taxAddress']);
        $tr->setTaxCountry($data['taxCountry']);
        $tr->setTaxState($data['taxState']);
        $tr->setTaxCity($data['taxCity']);
        $tr->save();
        
        return $tr;
    }
    public function save()
    {
        $em = Database::get()->getEntityManager();
        $em->persist($this);
        $em->flush();
    }
    
    public function delete()
    {
        $em = Database::get()->getEntityManager();
        $em->remove($this);
        $em->flush();
    }
    
    public static function getTaxRates()
    {
        $em = Database::get()->getEntityManager();
        $taxRates = $em->createQuery('select u from \Concrete\Package\VividStore\Src\VividStore\Tax\TaxRate u')->getResult();
        return $taxRates;
    }
    
}