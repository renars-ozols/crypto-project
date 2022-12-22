<?php

namespace App\Services\UserDashboard;

use App\Models\Collections\ShortSellOrderCollection;

class ShowShortSellOrdersResponse
{
    private ?ShortSellOrderCollection $openShortSellOrders;
    private ?ShortSellOrderCollection $closedShortSellOrders;

    public function __construct(?ShortSellOrderCollection $openShortSellOrders = null,
                                ?ShortSellOrderCollection $closedShortSellOrders = null)
    {
        $this->openShortSellOrders = $openShortSellOrders;
        $this->closedShortSellOrders = $closedShortSellOrders;
    }

    public function getOpenShortSellOrders(): ?ShortSellOrderCollection
    {
        return $this->openShortSellOrders;
    }

    public function getClosedShortSellOrders(): ?ShortSellOrderCollection
    {
        return $this->closedShortSellOrders;
    }
}