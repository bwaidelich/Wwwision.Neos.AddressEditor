<?php
namespace Wwwision\Neos\AddressEditor;

use Neos\ContentRepository\Domain\Model\Node;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\Flow\Core\Bootstrap;
use Neos\Flow\Package\Package as BasePackage;

class Package extends BasePackage
{

    /**
     * @param Bootstrap $bootstrap The current bootstrap
     * @return void
     */
    public function boot(Bootstrap $bootstrap)
    {
        $dispatcher = $bootstrap->getSignalSlotDispatcher();
        $dispatcher->connect(Node::class, 'beforeNodePropertyChange', function (NodeInterface $node, $propertyName, $_, $postalAddress) use ($bootstrap) {
            if (!$postalAddress instanceof PostalAddress || !(empty($postalAddress->latitude) && empty($postalAddress->longitude))) {
                return;
            }
            /** @var GeoCodingService $geoCodingService */
            $geoCodingService = $bootstrap->getObjectManager()->get(GeoCodingService::class);
            $coordinates = $geoCodingService->getCoordinatesForPostalAddress($postalAddress);
            // coordinates could not be determined
            if ($coordinates === null) {
                return;
            }
            $postalAddress->latitude = $coordinates->getLatitude();
            $postalAddress->longitude = $coordinates->getLongitude();
            $node->setProperty($propertyName, $postalAddress);
        });
    }
}