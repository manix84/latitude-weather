<?php

    class Weather {

        protected $_latitude;
        protected $_longitude;
        protected $_currentWeather;
        protected $_forcastWeather;

        /**
         * Google Weather API url.
         */
        const API_URL = 'http://www.google.com/ig/api?weather=,,,'; // LatLong location (EG: )
        // const API_URL = 'http://www.google.com/ig/api?weather='; // String location (EG: London)
        const CURRENT_XPATH = '/xml_api_reply/weather/current_conditions';
        const FORCAST_XPATH = '/xml_api_reply/weather/forecast_conditions';

        function __construct ($latitude, $longitude) {
            if ($latitude && $longitude) {
                $this->_latitude = $latitude;
                $this->_longitude = $longitude;
                $this->getWeather();
            } else {
                throw new Exception('Location not set');
            }
        }

        private function getWeather () {
            $weatherXML = simplexml_load_file(self::API_URL . $this->_latitude . ',' . $this->_longitude);

            $current = $weatherXML->xpath(self::CURRENT_XPATH);
            $this->_currentWeather = $current[0];

            $forcast = $weatherXML->xpath(self::FORCAST_XPATH);
            $this->_forcastWeather = $forcast[0];
        }



        public function getDayNight () {
            $sunset = date_sunset(time(), SUNFUNCS_RET_TIMESTAMP, $this->latitude, $this->longitude);
            $sunrise = date_sunrise(time(), SUNFUNCS_RET_TIMESTAMP, $this->latitude, $this->longitude);

            if ($sunrise < $sunset) {
                if ((time() > $sunrise) && (time() < $sunset)) {
                    return "day";
                } else {
                    return "night";
                }
            } else {
                if ((time() > $sunrise) || (time() < $sunset)) {
                    return "day";
                } else {
                    return "night";
                }
            }
        }

        public function getTempC () {
            return (string)$this->_currentWeather->temp_c['data'];
        }

        public function getTempF () {
            return (string)$this->_currentWeather->temp_f['data'];
        }

        public function getCondition () {
            $condition = (string)$this->_currentWeather->condition['data'];
            return strtolower($condition);
        }

        public function getHumidity () {
            return (string)$this->_currentWeather->humidity['data'];
        }

        public function getWindCondition () {
            return (string)$this->_currentWeather->wind_condition['data'];
        }

        public function getForcastConditions () {
            return (string)$this->_forcastWeather->condition['data'];
        }

    }