<?php


/**
 *
 * @var string smartpurge request url
 */
const LIMELIGHT_API_URL = "https://purge.llnw.com/purge/v1";

/**
 *
 * @var email constants
 */
const EMAIL_SUBJECT     = "purge results";
const EMAIL_TO          = "user@example.com";  // change your current username

/**
 *
 * @var string callback url for limelight to post purge results
 */
const CALLBACK_URL      = "http://test.example.com/my_callback.php";