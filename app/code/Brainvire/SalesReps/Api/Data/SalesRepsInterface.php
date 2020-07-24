<?php


namespace Brainvire\SalesReps\Api\Data;

interface SalesRepsInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    const SALES_PERSON_NAME = 'sales_person_name';
    const SALES_PERSON_CODE = 'sales_person_code';
    const SALES_MANAGER_CODE = 'sales_manager_code';
    const SALES_PERSON_DIVISION = 'sales_person_division';
    const SALESREPS_ID = 'salesreps_id';
    const SALES_MANAGER_DIVISION = 'sales_manager_division';

    /**
     * Get salesreps_id
     * @return string|null
     */
    public function getSalesrepsId();

    /**
     * Set salesreps_id
     * @param string $salesrepsId
     * @return \Brainvire\SalesReps\Api\Data\SalesRepsInterface
     */
    public function setSalesrepsId($salesrepsId);

    /**
     * Get sales_person_code
     * @return string|null
     */
    public function getSalesPersonCode();

    /**
     * Set sales_person_code
     * @param string $salesPersonCode
     * @return \Brainvire\SalesReps\Api\Data\SalesRepsInterface
     */
    public function setSalesPersonCode($salesPersonCode);

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Brainvire\SalesReps\Api\Data\SalesRepsExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \Brainvire\SalesReps\Api\Data\SalesRepsExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Brainvire\SalesReps\Api\Data\SalesRepsExtensionInterface $extensionAttributes
    );

    /**
     * Get sales_person_name
     * @return string|null
     */
    public function getSalesPersonName();

    /**
     * Set sales_person_name
     * @param string $salesPersonName
     * @return \Brainvire\SalesReps\Api\Data\SalesRepsInterface
     */
    public function setSalesPersonName($salesPersonName);

    /**
     * Get sales_person_division
     * @return string|null
     */
    public function getSalesPersonDivision();

    /**
     * Set sales_person_division
     * @param string $salesPersonDivision
     * @return \Brainvire\SalesReps\Api\Data\SalesRepsInterface
     */
    public function setSalesPersonDivision($salesPersonDivision);

    /**
     * Get sales_manager_division
     * @return string|null
     */
    public function getSalesManagerDivision();

    /**
     * Set sales_manager_division
     * @param string $salesManagerDivision
     * @return \Brainvire\SalesReps\Api\Data\SalesRepsInterface
     */
    public function setSalesManagerDivision($salesManagerDivision);

    /**
     * Get sales_manager_code
     * @return string|null
     */
    public function getSalesManagerCode();

    /**
     * Set sales_manager_code
     * @param string $salesManagerCode
     * @return \Brainvire\SalesReps\Api\Data\SalesRepsInterface
     */
    public function setSalesManagerCode($salesManagerCode);
}
