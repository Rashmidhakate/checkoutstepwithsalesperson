<?php


namespace Brainvire\SalesReps\Model\Data;

use Brainvire\SalesReps\Api\Data\SalesRepsInterface;

class SalesReps extends \Magento\Framework\Api\AbstractExtensibleObject implements SalesRepsInterface
{

    /**
     * Get salesreps_id
     * @return string|null
     */
    public function getSalesrepsId()
    {
        return $this->_get(self::SALESREPS_ID);
    }

    /**
     * Set salesreps_id
     * @param string $salesrepsId
     * @return \Brainvire\SalesReps\Api\Data\SalesRepsInterface
     */
    public function setSalesrepsId($salesrepsId)
    {
        return $this->setData(self::SALESREPS_ID, $salesrepsId);
    }

    /**
     * Get sales_person_code
     * @return string|null
     */
    public function getSalesPersonCode()
    {
        return $this->_get(self::SALES_PERSON_CODE);
    }

    /**
     * Set sales_person_code
     * @param string $salesPersonCode
     * @return \Brainvire\SalesReps\Api\Data\SalesRepsInterface
     */
    public function setSalesPersonCode($salesPersonCode)
    {
        return $this->setData(self::SALES_PERSON_CODE, $salesPersonCode);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Brainvire\SalesReps\Api\Data\SalesRepsExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Brainvire\SalesReps\Api\Data\SalesRepsExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Brainvire\SalesReps\Api\Data\SalesRepsExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * Get sales_person_name
     * @return string|null
     */
    public function getSalesPersonName()
    {
        return $this->_get(self::SALES_PERSON_NAME);
    }

    /**
     * Set sales_person_name
     * @param string $salesPersonName
     * @return \Brainvire\SalesReps\Api\Data\SalesRepsInterface
     */
    public function setSalesPersonName($salesPersonName)
    {
        return $this->setData(self::SALES_PERSON_NAME, $salesPersonName);
    }

    /**
     * Get sales_person_division
     * @return string|null
     */
    public function getSalesPersonDivision()
    {
        return $this->_get(self::SALES_PERSON_DIVISION);
    }

    /**
     * Set sales_person_division
     * @param string $salesPersonDivision
     * @return \Brainvire\SalesReps\Api\Data\SalesRepsInterface
     */
    public function setSalesPersonDivision($salesPersonDivision)
    {
        return $this->setData(self::SALES_PERSON_DIVISION, $salesPersonDivision);
    }

    /**
     * Get sales_manager_division
     * @return string|null
     */
    public function getSalesManagerDivision()
    {
        return $this->_get(self::SALES_MANAGER_DIVISION);
    }

    /**
     * Set sales_manager_division
     * @param string $salesManagerDivision
     * @return \Brainvire\SalesReps\Api\Data\SalesRepsInterface
     */
    public function setSalesManagerDivision($salesManagerDivision)
    {
        return $this->setData(self::SALES_MANAGER_DIVISION, $salesManagerDivision);
    }

    /**
     * Get sales_manager_code
     * @return string|null
     */
    public function getSalesManagerCode()
    {
        return $this->_get(self::SALES_MANAGER_CODE);
    }

    /**
     * Set sales_manager_code
     * @param string $salesManagerCode
     * @return \Brainvire\SalesReps\Api\Data\SalesRepsInterface
     */
    public function setSalesManagerCode($salesManagerCode)
    {
        return $this->setData(self::SALES_MANAGER_CODE, $salesManagerCode);
    }
}
