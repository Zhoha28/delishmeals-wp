<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/ads/googleads/v8/common/asset_types.proto

namespace Google\Ads\GoogleAds\V8\Common;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * A Sitelink asset.
 *
 * Generated from protobuf message <code>google.ads.googleads.v8.common.SitelinkAsset</code>
 */
class SitelinkAsset extends \Google\Protobuf\Internal\Message
{
    /**
     * Required. URL display text for the sitelink.
     * The length of this string should be between 1 and 25, inclusive.
     *
     * Generated from protobuf field <code>string link_text = 1 [(.google.api.field_behavior) = REQUIRED];</code>
     */
    protected $link_text = '';
    /**
     * First line of the description for the sitelink.
     * If set, the length should be between 1 and 35, inclusive, and description2
     * must also be set.
     *
     * Generated from protobuf field <code>string description1 = 2;</code>
     */
    protected $description1 = '';
    /**
     * Second line of the description for the sitelink.
     * If set, the length should be between 1 and 35, inclusive, and description1
     * must also be set.
     *
     * Generated from protobuf field <code>string description2 = 3;</code>
     */
    protected $description2 = '';
    /**
     * Start date of when this asset is effective and can begin serving, in
     * yyyy-MM-dd format.
     *
     * Generated from protobuf field <code>string start_date = 4;</code>
     */
    protected $start_date = '';
    /**
     * Last date of when this asset is effective and still serving, in yyyy-MM-dd
     * format.
     *
     * Generated from protobuf field <code>string end_date = 5;</code>
     */
    protected $end_date = '';
    /**
     * List of non-overlapping schedules specifying all time intervals for which
     * the asset may serve. There can be a maximum of 6 schedules per day, 42 in
     * total.
     *
     * Generated from protobuf field <code>repeated .google.ads.googleads.v8.common.AdScheduleInfo ad_schedule_targets = 6;</code>
     */
    private $ad_schedule_targets;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $link_text
     *           Required. URL display text for the sitelink.
     *           The length of this string should be between 1 and 25, inclusive.
     *     @type string $description1
     *           First line of the description for the sitelink.
     *           If set, the length should be between 1 and 35, inclusive, and description2
     *           must also be set.
     *     @type string $description2
     *           Second line of the description for the sitelink.
     *           If set, the length should be between 1 and 35, inclusive, and description1
     *           must also be set.
     *     @type string $start_date
     *           Start date of when this asset is effective and can begin serving, in
     *           yyyy-MM-dd format.
     *     @type string $end_date
     *           Last date of when this asset is effective and still serving, in yyyy-MM-dd
     *           format.
     *     @type \Google\Ads\GoogleAds\V8\Common\AdScheduleInfo[]|\Google\Protobuf\Internal\RepeatedField $ad_schedule_targets
     *           List of non-overlapping schedules specifying all time intervals for which
     *           the asset may serve. There can be a maximum of 6 schedules per day, 42 in
     *           total.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Ads\GoogleAds\V8\Common\AssetTypes::initOnce();
        parent::__construct($data);
    }

    /**
     * Required. URL display text for the sitelink.
     * The length of this string should be between 1 and 25, inclusive.
     *
     * Generated from protobuf field <code>string link_text = 1 [(.google.api.field_behavior) = REQUIRED];</code>
     * @return string
     */
    public function getLinkText()
    {
        return $this->link_text;
    }

    /**
     * Required. URL display text for the sitelink.
     * The length of this string should be between 1 and 25, inclusive.
     *
     * Generated from protobuf field <code>string link_text = 1 [(.google.api.field_behavior) = REQUIRED];</code>
     * @param string $var
     * @return $this
     */
    public function setLinkText($var)
    {
        GPBUtil::checkString($var, True);
        $this->link_text = $var;

        return $this;
    }

    /**
     * First line of the description for the sitelink.
     * If set, the length should be between 1 and 35, inclusive, and description2
     * must also be set.
     *
     * Generated from protobuf field <code>string description1 = 2;</code>
     * @return string
     */
    public function getDescription1()
    {
        return $this->description1;
    }

    /**
     * First line of the description for the sitelink.
     * If set, the length should be between 1 and 35, inclusive, and description2
     * must also be set.
     *
     * Generated from protobuf field <code>string description1 = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setDescription1($var)
    {
        GPBUtil::checkString($var, True);
        $this->description1 = $var;

        return $this;
    }

    /**
     * Second line of the description for the sitelink.
     * If set, the length should be between 1 and 35, inclusive, and description1
     * must also be set.
     *
     * Generated from protobuf field <code>string description2 = 3;</code>
     * @return string
     */
    public function getDescription2()
    {
        return $this->description2;
    }

    /**
     * Second line of the description for the sitelink.
     * If set, the length should be between 1 and 35, inclusive, and description1
     * must also be set.
     *
     * Generated from protobuf field <code>string description2 = 3;</code>
     * @param string $var
     * @return $this
     */
    public function setDescription2($var)
    {
        GPBUtil::checkString($var, True);
        $this->description2 = $var;

        return $this;
    }

    /**
     * Start date of when this asset is effective and can begin serving, in
     * yyyy-MM-dd format.
     *
     * Generated from protobuf field <code>string start_date = 4;</code>
     * @return string
     */
    public function getStartDate()
    {
        return $this->start_date;
    }

    /**
     * Start date of when this asset is effective and can begin serving, in
     * yyyy-MM-dd format.
     *
     * Generated from protobuf field <code>string start_date = 4;</code>
     * @param string $var
     * @return $this
     */
    public function setStartDate($var)
    {
        GPBUtil::checkString($var, True);
        $this->start_date = $var;

        return $this;
    }

    /**
     * Last date of when this asset is effective and still serving, in yyyy-MM-dd
     * format.
     *
     * Generated from protobuf field <code>string end_date = 5;</code>
     * @return string
     */
    public function getEndDate()
    {
        return $this->end_date;
    }

    /**
     * Last date of when this asset is effective and still serving, in yyyy-MM-dd
     * format.
     *
     * Generated from protobuf field <code>string end_date = 5;</code>
     * @param string $var
     * @return $this
     */
    public function setEndDate($var)
    {
        GPBUtil::checkString($var, True);
        $this->end_date = $var;

        return $this;
    }

    /**
     * List of non-overlapping schedules specifying all time intervals for which
     * the asset may serve. There can be a maximum of 6 schedules per day, 42 in
     * total.
     *
     * Generated from protobuf field <code>repeated .google.ads.googleads.v8.common.AdScheduleInfo ad_schedule_targets = 6;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getAdScheduleTargets()
    {
        return $this->ad_schedule_targets;
    }

    /**
     * List of non-overlapping schedules specifying all time intervals for which
     * the asset may serve. There can be a maximum of 6 schedules per day, 42 in
     * total.
     *
     * Generated from protobuf field <code>repeated .google.ads.googleads.v8.common.AdScheduleInfo ad_schedule_targets = 6;</code>
     * @param \Google\Ads\GoogleAds\V8\Common\AdScheduleInfo[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setAdScheduleTargets($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \Google\Ads\GoogleAds\V8\Common\AdScheduleInfo::class);
        $this->ad_schedule_targets = $arr;

        return $this;
    }

}

