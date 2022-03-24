<?php

declare(strict_types=1);

namespace SevenInteractive\Model\Helper;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use SevenInteractive\Model\Entity\Day;
use SevenInteractive\Model\Facade\DayFacade;
use SevenInteractive\Model\Repository\DayRepository;

abstract class DayApiHelper
{

    public const BASE_API_URL = 'https://assets-7rs.cz/api';
    public const ENDPOINT_ALL_DAYS = '/days';
    public const ENDPOINT_NAME_DAYS = '/days/name-days';
    public const ENDPOINT_PUBLIC_HOLIDAYS = '/days/public-holidays';
    public const ENDPOINT_NATIONAL_HOLIDAYS = '/days/national-holidays';

    /** @var Client */
    private $client;

    /** @var DayRepository */
    private $dayRepository;

    /** @var DayFacade */
    private $dayFacade;

    public function __construct(DayRepository $dayRepository, DayFacade $dayFacade)
    {
        $this->client = new Client([
            'base_uri' => self::BASE_API_URL,
            'verify' => false
        ]);
        $this->dayRepository = $dayRepository;
        $this->dayFacade = $dayFacade;
    }

    protected function sendRequest(string $method, string $uri, ?int $year = null, ?int $month = null): bool
    {
        if($year !== null){
            $uri .= '/'.$year;
        }

        if($month !== null){
            $uri .= '/'.$month;
        }

        try {
            $response = $this->client->request($method, $uri);
            $result = json_decode($response->getBody()->getContents(), true);
            $data = $result['data'] ?? null;

            foreach ($data as $day){
                $name = $day['name'];
                $date = new \DateTime($day['date']);

                $conflictingDays = $this->dayRepository->findForDay($date, $name);
                if(empty($conflictingDays)){
                    $className = $this->dayRepository->getChildClassName();
                    /** @var Day $dayEntity */
                    $dayEntity = new $className;
                    $dayEntity
                        ->setName($day['name'])
                        ->setDate(new \DateTime($day['date']));

                    $this->dayFacade->persistAndFlush($dayEntity);
                }
            }

            return true;
        } catch (RequestException $exception) {
            return false;
        }
    }

    public function getAllDays(?int $year = null, ?int $month = null): bool
    {
        return $this->sendRequest('GET', self::ENDPOINT_ALL_DAYS, $year, $month);
    }

    public function getPublicHolidays(?int $year = null, ?int $month = null): bool
    {
        return $this->sendRequest('GET', self::ENDPOINT_PUBLIC_HOLIDAYS, $year, $month);
    }

    public function getNationalHolidays(?int $year = null, ?int $month = null): bool
    {
        return $this->sendRequest('GET', self::ENDPOINT_NATIONAL_HOLIDAYS, $year, $month);
    }

    public function getNameDays(?int $year = null, ?int $month = null): bool
    {
        return $this->sendRequest('GET', self::ENDPOINT_NAME_DAYS, $year, $month);
    }

}