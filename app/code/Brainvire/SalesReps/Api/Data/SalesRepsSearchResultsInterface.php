<?php


namespace Brainvire\SalesReps\Api\Data;

interface SalesRepsSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get SalesReps list.
     * @return \Brainvire\SalesReps\Api\Data\SalesRepsInterface[]
     */
    public function getItems();

    /**
     * Set sales_person_code list.
     * @param \Brainvire\SalesReps\Api\Data\SalesRepsInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
