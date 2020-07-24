<?php


namespace Brainvire\SalesReps\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface SalesRepsRepositoryInterface
{

    /**
     * Save SalesReps
     * @param \Brainvire\SalesReps\Api\Data\SalesRepsInterface $salesReps
     * @return \Brainvire\SalesReps\Api\Data\SalesRepsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Brainvire\SalesReps\Api\Data\SalesRepsInterface $salesReps
    );

    /**
     * Retrieve SalesReps
     * @param string $salesrepsId
     * @return \Brainvire\SalesReps\Api\Data\SalesRepsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($salesrepsId);

    /**
     * Retrieve SalesReps matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Brainvire\SalesReps\Api\Data\SalesRepsSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete SalesReps
     * @param \Brainvire\SalesReps\Api\Data\SalesRepsInterface $salesReps
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Brainvire\SalesReps\Api\Data\SalesRepsInterface $salesReps
    );

    /**
     * Delete SalesReps by ID
     * @param string $salesrepsId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($salesrepsId);
}
