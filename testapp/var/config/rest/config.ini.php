;<?php die(''); ?>
;for security reasons , don't remove or modify the first line

[coordplugins]
jacl2=1
jacl=1

[responses]
html=myHtmlResponse
soap="jsoap~jResponseSoap"


[urlengine]
significantFile=urls_rest.xml


[simple_urlengine_entrypoints]
soap="@soap"
[basic_significant_urlengine_entrypoints]
soap=1
