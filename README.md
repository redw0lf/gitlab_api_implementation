# Gitlab API implementations for Slack

These scripts should help to get data from slack into your gitlab installation. I plan to add more Implementations to it. Right now there is only a PHP implementation. But creating the same for JavaScript should be quite simple

## Installation

1. Define an ougoing Webhook in Slack (as URL use the url where you hosted the php script)
2. Set the variables or use the URL additions (see comments in the php code)
  1. Slack Token
  2. Stored token (Gitlab api token, you can find this in your user setings)
  3. Project ID
3. define a trigger word -> you are ready to go

## How it works

Simply write a message in Slack like: 

``issue: bug the script does not post responses correctly``

wheres ``issue:`` is the trigger word. ``bug`` is the label -> multiple labels can be seperated by a , e.g. like ``bug,feature,important``. Everything after the label is the issue topic

