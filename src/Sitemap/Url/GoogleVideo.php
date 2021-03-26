<?php

/**
 * This file is part of the PrestaSitemapBundle package.
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Presta\SitemapBundle\Sitemap\Url;

use DateTimeInterface;
use Presta\SitemapBundle\Exception;
use Presta\SitemapBundle\Sitemap\Utils;

/**
 * Class used for managing video's url entities
 *
 * @author David Epely <depely@prestaconcept.net>
 */
class GoogleVideo
{
    public const PLAYER_LOC_ALLOW_EMBED_YES = 'yes';
    public const PLAYER_LOC_ALLOW_EMBED_NO = 'no';
    public const FAMILY_FRIENDLY_YES = 'yes';
    public const FAMILY_FRIENDLY_NO = 'no';
    public const RELATIONSHIP_ALLOW = 'allow';
    public const RELATIONSHIP_DENY = 'deny';
    public const PRICE_TYPE_RENT = 'rent';
    public const PRICE_TYPE_OWN = 'own';
    public const PRICE_RESOLUTION_HD = 'HD';
    public const PRICE_RESOLUTION_SD = 'SD';
    public const REQUIRES_SUBSCRIPTION_YES = 'yes';
    public const REQUIRES_SUBSCRIPTION_NO = 'no';
    public const PLATFORM_WEB = 'web';
    public const PLATFORM_MOBILE = 'mobile';
    public const PLATFORM_TV = 'tv';
    public const PLATFORM_RELATIONSHIP_ALLOW = 'allow';
    public const PLATFORM_RELATIONSHIP_DENY = 'deny';
    public const LIVE_YES = 'yes';
    public const LIVE_NO = 'no';
    public const TAG_ITEMS_LIMIT = 32;

    /**
     * @var string
     */
    protected $thumbnailLocation;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $description;

    //list of optional parameters

    /**
     * @var string|null
     */
    protected $contentLocation;

    /**
     * @var string|null
     */
    protected $playerLocation;

    /**
     * allow google to embed video in search results
     * @var string
     */
    protected $playerLocationAllowEmbed;

    /**
     * user defined string for flashvar parameters in embed tag (e.g. autoplay="ap=1")
     * @var string
     */
    protected $playerLocationAutoplay;

    /**
     * @var int|null
     */
    protected $duration;

    /**
     * @var DateTimeInterface|null
     */
    protected $expirationDate;

    /**
     * @var int|null
     */
    protected $rating;

    /**
     * @var int|null
     */
    protected $viewCount;

    /**
     * @var DateTimeInterface|null
     */
    protected $publicationDate;

    /**
     * @var string|null
     */
    protected $familyFriendly;

    /**
     * @var string|null
     */
    protected $category;

    /**
     * @var array
     */
    protected $restrictionAllow = [];

    /**
     * @var array
     */
    protected $restrictionDeny = [];

    /**
     * @var string|null
     */
    protected $galleryLocation;

    /**
     * @var string|null
     */
    protected $galleryLocationTitle;

    /**
     * @var string|null
     */
    protected $requiresSubscription;

    /**
     * @var string|null
     */
    protected $uploader;

    /**
     * @var string|null
     */
    protected $uploaderInfo;

    /**
     * @var array
     */
    protected $platforms = [];

    /**
     * @var string|null
     */
    protected $platformRelationship;

    /**
     * @var string|null
     */
    protected $live;

    /**
     * multiple prices can be added, see self::addPrice()
     * @var array
     */
    protected $prices = [];

    /**
     * multiple tags can be added, see self::addTag()
     * @var array
     */
    protected $tags = [];

    /**
     * create a GoogleImage for your GoogleImageUrl
     *
     * @param string $thumbnailLocation
     * @param string $title
     * @param string $description
     * @param array  $parameters Properties of this class, (e.g. 'player_loc' => 'http://acme.com/player.swf')
     *
     * @throws Exception\GoogleVideoException
     */
    public function __construct($thumbnailLocation, $title, $description, array $parameters = [])
    {
        foreach ($parameters as $key => $param) {
            switch ($key) {
                case 'content_location':
                    $this->setContentLocation($param);
                    break;
                case 'player_location':
                    $this->setPlayerLocation($param);
                    break;
                case 'player_location_allow_embed':
                    $this->setPlayerLocationAllowEmbed($param);
                    break;
                case 'player_location_autoplay':
                    $this->setPlayerLocationAutoplay($param);
                    break;
                case 'duration':
                    $this->setDuration($param);
                    break;
                case 'expiration_date':
                    $this->setExpirationDate($param);
                    break;
                case 'rating':
                    $this->setRating($param);
                    break;
                case 'view_count':
                    $this->setViewCount($param);
                    break;
                case 'publication_date':
                    $this->setPublicationDate($param);
                    break;
                case 'family_friendly':
                    $this->setFamilyFriendly($param);
                    break;
                case 'category':
                    $this->setCategory($param);
                    break;
                case 'restriction_allow':
                    $this->setRestrictionAllow($param);
                    break;
                case 'restriction_deny':
                    $this->setRestrictionDeny($param);
                    break;
                case 'gallery_location':
                    $this->setGalleryLocation($param);
                    break;
                case 'gallery_location_title':
                    $this->setGalleryLocationTitle($param);
                    break;
                case 'requires_subscription':
                    $this->setRequiresSubscription($param);
                    break;
                case 'uploader':
                    $this->setUploader($param);
                    break;
                case 'uploader_info':
                    $this->setUploaderInfo($param);
                    break;
                case 'platforms':
                    $this->setPlatforms($param);
                    break;
                case 'platform_relationship':
                    $this->setPlatformRelationship($param);
                    break;
                case 'live':
                    $this->setLive($param);
                    break;
            }
        }

        $this->setThumbnailLocation($thumbnailLocation);
        $this->setTitle($title);
        $this->setDescription($description);

        if (!$this->contentLocation && !$this->playerLocation) {
            throw new Exception\GoogleVideoException('The parameter content_location or content_location is required');
        }

        if (count($this->platforms) && !$this->platformRelationship) {
            throw new Exception\GoogleVideoException(
                'The parameter platform_relationship is required when platform is set'
            );
        }
    }

    /**
     * @param string $location
     *
     * @return GoogleVideo
     */
    public function setThumbnailLocation($location)
    {
        $this->thumbnailLocation = $location;

        return $this;
    }

    /**
     * @return string
     */
    public function getThumbnailLocation()
    {
        return $this->thumbnailLocation;
    }

    /**
     * @param string $title
     *
     * @return GoogleVideo
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param string $description
     *
     * @return GoogleVideo
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param string $location
     *
     * @return GoogleVideo
     */
    public function setContentLocation($location)
    {
        $this->contentLocation = $location;

        return $this;
    }

    /**
     * @param string $location
     *
     * @return GoogleVideo
     */
    public function setPlayerLocation($location)
    {
        $this->playerLocation = $location;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPlayerLocation()
    {
        return $this->playerLocation;
    }

    /**
     * @param string $embed
     *
     * @return GoogleVideo
     */
    public function setPlayerLocationAllowEmbed($embed)
    {
        if (!in_array($embed, [self::PLAYER_LOC_ALLOW_EMBED_YES, self::PLAYER_LOC_ALLOW_EMBED_NO])) {
            throw new Exception\GoogleVideoException(
                sprintf(
                    'The parameter %s must be a valid player_location_allow_embed.' .
                    ' See http://support.google.com/webmasters/bin/answer.py?hl=en&answer=80472#4',
                    $embed
                )
            );
        }
        $this->playerLocationAllowEmbed = $embed;

        return $this;
    }

    /**
     * @return string
     */
    public function getPlayerLocationAllowEmbed()
    {
        return $this->playerLocationAllowEmbed;
    }

    /**
     * @param string $autoplay
     *
     * @return GoogleVideo
     */
    public function setPlayerLocationAutoplay($autoplay)
    {
        $this->playerLocationAutoplay = $autoplay;

        return $this;
    }

    /**
     * @return string
     */
    public function getPlayerLocationAutoplay()
    {
        return $this->playerLocationAutoplay;
    }

    /**
     * @param int $duration
     *
     * @return GoogleVideo
     * @throws Exception\GoogleVideoException
     */
    public function setDuration($duration)
    {
        if ($duration < 0 || $duration > 28800) {
            throw new Exception\GoogleVideoException(
                sprintf(
                    'The parameter %s must be a valid duration.' .
                    ' See http://support.google.com/webmasters/bin/answer.py?hl=en&answer=80472#4',
                    $duration
                )
            );
        }

        $this->duration = $duration;

        return $this;
    }

    /**
     * @param DateTimeInterface $expirationDate
     *
     * @return GoogleVideo
     */
    public function setExpirationDate(DateTimeInterface $expirationDate)
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }

    /**
     * @param float $rating
     *
     * @return GoogleVideo
     */
    public function setRating($rating)
    {
        if ($rating < 0 || $rating > 5) {
            throw new Exception\GoogleVideoException(
                sprintf(
                    'The parameter %s must be a valid rating.' .
                    ' See http://support.google.com/webmasters/bin/answer.py?hl=en&answer=80472#4',
                    $rating
                )
            );
        }

        $this->rating = $rating;

        return $this;
    }

    /**
     * @param int $viewCount
     *
     * @return GoogleVideo
     */
    public function setViewCount($viewCount)
    {
        $this->viewCount = $viewCount;

        return $this;
    }

    /**
     * @param DateTimeInterface $publicationDate
     *
     * @return GoogleVideo
     */
    public function setPublicationDate(DateTimeInterface $publicationDate)
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    /**
     * @param null|string $familyFriendly
     *
     * @return GoogleVideo
     */
    public function setFamilyFriendly($familyFriendly = null)
    {
        if (null == $familyFriendly) {
            $familyFriendly = self::FAMILY_FRIENDLY_YES;
        }

        if (!in_array($familyFriendly, [self::FAMILY_FRIENDLY_YES, self::FAMILY_FRIENDLY_NO])) {
            throw new Exception\GoogleVideoException(
                sprintf(
                    'The parameter %s must be a valid family_friendly.' .
                    ' See http://support.google.com/webmasters/bin/answer.py?hl=en&answer=80472#4',
                    $familyFriendly
                )
            );
        }

        $this->familyFriendly = $familyFriendly;

        return $this;
    }

    /**
     * @param string $category
     *
     * @return GoogleVideo
     */
    public function setCategory($category)
    {
        if (strlen($category) > 256) {
            throw new Exception\GoogleVideoException(
                sprintf(
                    'The parameter %s must be a valid category.' .
                    ' See http://support.google.com/webmasters/bin/answer.py?hl=en&answer=80472#4',
                    $category
                )
            );
        }

        $this->category = $category;

        return $this;
    }

    /**
     * @param array $countries
     *
     * @return GoogleVideo
     */
    public function setRestrictionAllow(array $countries)
    {
        $this->restrictionAllow = $countries;

        return $this;
    }

    /**
     * @return array
     */
    public function getRestrictionAllow()
    {
        return $this->restrictionAllow;
    }

    /**
     * @param array $countries
     *
     * @return GoogleVideo
     */
    public function setRestrictionDeny(array $countries)
    {
        $this->restrictionDeny = $countries;

        return $this;
    }

    /**
     * @return array
     */
    public function getRestrictionDeny()
    {
        return $this->restrictionDeny;
    }

    /**
     * @param string $location
     *
     * @return GoogleVideo
     */
    public function setGalleryLocation($location)
    {
        $this->galleryLocation = $location;

        return $this;
    }

    /**
     * @param string $title
     *
     * @return GoogleVideo
     */
    public function setGalleryLocationTitle($title)
    {
        $this->galleryLocationTitle = $title;

        return $this;
    }

    /**
     * @param string $requiresSubscription
     *
     * @return GoogleVideo
     */
    public function setRequiresSubscription($requiresSubscription)
    {
        if (!in_array($requiresSubscription, [self::REQUIRES_SUBSCRIPTION_YES, self::REQUIRES_SUBSCRIPTION_NO])) {
            throw new Exception\GoogleVideoException(
                sprintf(
                    'The parameter %s must be a valid requires_subscription.' .
                    ' See http://support.google.com/webmasters/bin/answer.py?hl=en&answer=80472#4',
                    $requiresSubscription
                )
            );
        }

        $this->requiresSubscription = $requiresSubscription;

        return $this;
    }

    /**
     * @param string $uploader
     *
     * @return GoogleVideo
     */
    public function setUploader($uploader)
    {
        $this->uploader = $uploader;

        return $this;
    }

    /**
     * @param string $uploaderInfo
     *
     * @return GoogleVideo
     */
    public function setUploaderInfo($uploaderInfo)
    {
        $this->uploaderInfo = $uploaderInfo;

        return $this;
    }

    /**
     * @param array $platforms
     *
     * @return GoogleVideo
     */
    public function setPlatforms(array $platforms)
    {
        $this->platforms = $platforms;

        return $this;
    }

    /**
     * @return array
     */
    public function getPlatforms()
    {
        return $this->platforms;
    }

    /**
     * @param string $platformRelationship
     *
     * @return GoogleVideo
     */
    public function setPlatformRelationship($platformRelationship)
    {
        $this->platformRelationship = $platformRelationship;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getPlatformRelationship()
    {
        return $this->platformRelationship;
    }

    /**
     * @param string $live
     *
     * @return GoogleVideo
     */
    public function setLive($live)
    {
        $this->live = $live;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return null|string
     */
    public function getContentLocation()
    {
        return $this->contentLocation;
    }

    /**
     * @return int|null
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getExpirationDate()
    {
        return $this->expirationDate;
    }

    /**
     * @return int|null
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @return int|null
     */
    public function getViewCount()
    {
        return $this->viewCount;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getPublicationDate()
    {
        return $this->publicationDate;
    }

    /**
     * @return null|string
     */
    public function getFamilyFriendly()
    {
        return $this->familyFriendly;
    }

    /**
     * @return null|string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @return null|string
     */
    public function getGalleryLocation()
    {
        return $this->galleryLocation;
    }

    /**
     * @return null|string
     */
    public function getGalleryLocationTitle()
    {
        return $this->galleryLocationTitle;
    }

    /**
     * @return null|string
     */
    public function getRequiresSubscription()
    {
        return $this->requiresSubscription;
    }

    /**
     * @return null|string
     */
    public function getUploader()
    {
        return $this->uploader;
    }

    /**
     * @return null|string
     */
    public function getUploaderInfo()
    {
        return $this->uploaderInfo;
    }

    /**
     * @return string|null
     */
    public function getLive()
    {
        return $this->live;
    }

    /**
     * add price element
     *
     * @param float       $amount
     * @param string      $currency   - ISO 4217 format.
     * @param string|null $type       - rent or own
     * @param string|null $resolution - hd or sd
     *
     * @return GoogleVideo
     */
    public function addPrice($amount, $currency, $type = null, $resolution = null)
    {
        $this->prices[] = [
            'amount' => $amount,
            'currency' => $currency,
            'type' => $type,
            'resolution' => $resolution,
        ];

        return $this;
    }

    /**
     * list of defined prices with price, currency, type and resolution
     *
     * @return array
     */
    public function getPrices()
    {
        return $this->prices;
    }

    /**
     * @param string $tag
     *
     * @return GoogleVideo
     * @throws Exception\GoogleVideoTagException
     */
    public function addTag($tag)
    {
        if (count($this->tags) >= self::TAG_ITEMS_LIMIT) {
            throw new Exception\GoogleVideoTagException(
                sprintf('The tags limit of %d items is exceeded.', self::TAG_ITEMS_LIMIT)
            );
        }

        $this->tags[] = $tag;

        return $this;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Return the xml representation for the video
     *
     * @return string
     */
    public function toXml()
    {
        $videoXml = '<video:video>';

        //----------------------
        // required fields
        $videoXml .= '<video:thumbnail_loc>' . Utils::encode($this->getThumbnailLocation()) . '</video:thumbnail_loc>';
        $videoXml .= '<video:title>' . Utils::cdata($this->getTitle()) . '</video:title>';
        $videoXml .= '<video:description>' . Utils::cdata($this->getDescription()) . '</video:description>';

        //----------------------
        //----------------------
        // simple optional fields
        if ($category = $this->getCategory()) {
            $videoXml .= '<video:category>' . Utils::cdata($category) . '</video:category>';
        }
        if ($location = $this->getContentLocation()) {
            $videoXml .= '<video:content_loc>' . Utils::encode($location) . '</video:content_loc>';
        }
        if ($duration = $this->getDuration()) {
            $videoXml .= '<video:duration>' . $duration . '</video:duration>';
        }
        if ($rating = $this->getRating()) {
            $videoXml .= '<video:rating>' . $rating . '</video:rating>';
        }
        if ($viewCount = $this->getViewCount()) {
            $videoXml .= '<video:view_count>' . $viewCount . '</video:view_count>';
        }
        if ($familyFriendly = $this->getFamilyFriendly()) {
            $videoXml .= '<video:family_friendly>' . $familyFriendly . '</video:family_friendly>';
        }
        if ($requiresSubscription = $this->getRequiresSubscription()) {
            $videoXml .= '<video:requires_subscription>' . $requiresSubscription . '</video:requires_subscription>';
        }
        if ($live = $this->getLive()) {
            $videoXml .= '<video:live>' . $live . '</video:live>';
        }

        //----------------------
        //----------------------
        // date based optional fields
        if ($date = $this->getExpirationDate()) {
            $videoXml .= '<video:expiration_date>' . $date->format('c') . '</video:expiration_date>';
        }
        if ($date = $this->getPublicationDate()) {
            $videoXml .= '<video:publication_date>' . $date->format('c') . '</video:publication_date>';
        }

        //----------------------
        //----------------------
        // more complex optional fields
        if ($playerLocation = $this->getPlayerLocation()) {
            $attributes = [];
            if ($uploaderInfo = $this->getPlayerLocationAllowEmbed()) {
                $attributes['allow_embed'] = Utils::encode($uploaderInfo);
            }
            if ($autoplay = $this->getPlayerLocationAutoplay()) {
                $attributes['autoplay'] = Utils::encode($autoplay);
            }

            $videoXml .= '<video:player_loc' . $this->attributes($attributes) . '>'
                . Utils::encode($playerLocation)
                . '</video:player_loc>';
        }

        if ($allow = $this->getRestrictionAllow()) {
            $videoXml .= '<video:restriction relationship="allow">' . implode(' ', $allow) . '</video:restriction>';
        }

        if ($deny = $this->getRestrictionDeny()) {
            $videoXml .= '<video:restriction relationship="deny">' . implode(' ', $deny) . '</video:restriction>';
        }

        if ($galleryLocation = $this->getGalleryLocation()) {
            $attributes = [];
            if ($galleryLocationTitle = $this->getGalleryLocationTitle()) {
                $attributes['title'] = Utils::encode($galleryLocationTitle);
            }

            $videoXml .= '<video:gallery_loc' . $this->attributes($attributes) . '>'
                . Utils::encode($galleryLocation)
                . '</video:gallery_loc>';
        }

        foreach ($this->getTags() as $tag) {
            $videoXml .= '<video:tag>' . Utils::cdata($tag) . '</video:tag>';
        }

        foreach ($this->getPrices() as $price) {
            $attributes = array_intersect_key($price, array_flip(['currency', 'type', 'resolution']));
            $attributes = array_filter($attributes);

            $videoXml .= '<video:price' . $this->attributes($attributes) . '>' . $price['amount'] . '</video:price>';
        }

        if ($uploader = $this->getUploader()) {
            $attributes = [];
            if ($uploaderInfo = $this->getUploaderInfo()) {
                $attributes['info'] = $uploaderInfo;
            }

            $videoXml .= '<video:uploader' . $this->attributes($attributes) . '>' . $uploader . '</video:uploader>';
        }

        if (count($platforms = $this->getPlatforms())) {
            $videoXml .= '<video:platform relationship="' . $this->getPlatformRelationship() . '">'
                . implode(' ', $platforms)
                . '</video:platform>';
        }
        //----------------------

        $videoXml .= '</video:video>';

        return $videoXml;
    }

    private function attributes(array $map): string
    {
        $attributes = '';
        if (\count($map) === 0) {
            return $attributes;
        }

        foreach ($map as $name => $value) {
            $attributes .= ' ' . $name . '="' . $value . '"';
        }

        return ' ' . trim($attributes);
    }
}
