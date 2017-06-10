<?php

namespace Scaville\Lemon\Core\Database;

interface ResultSet{
    function fetchRows();
    function fetchArray();
    function fetchObject();
    function fetchEntity();
}
