<?php
KLoader::loadIdentifier('com://admin/annotations.aliases');

echo KService::get('com://admin/annotations.dispatcher')->dispatch();