;<?php exit; ?>
;*** DO NOT REMOVE THE LINE ABOVE ***

[openads]
installed=1
requireSSL=
sslPort=443

[ui]
enabled=1
applicationName=
headerFilePath=
footerFilePath=
logoFilePath=
headerForegroundColor=
headerBackgroundColor=
headerActiveTabColor=
headerTextColor=
gzipCompression=1
combineAssets=1
showContactUsLink=
dashboardEnabled=1
hideNavigator=
zoneLinkingStatistics=1

[database]
type=mysql
host=localhost
socket=
port=3306
username=root
password=rgbxyz
name=audianz
persistent=
mysql4_compatibility=1
protocol=tcp
compress=
ssl=
capath=
ca=

[databaseCharset]
checkComplete=1
clientCharset=

[databaseMysql]
statisticsSortBufferSize=

[databasePgsql]
schema=

[webpath]
admin="54.235.252.159/ads/www/admin"
delivery="54.235.252.159/ads/www/delivery"
deliverySSL="54.235.252.159/ads/www/delivery"
images="54.235.252.159/ads/www/images"
imagesSSL="54.235.252.159/ads/www/images"
rectBanner="54.235.252.159/assets/upload/rectBanner"
[file]
click="ck.php"
conversionvars="tv.php"
content="ac.php"
conversion="ti.php"
conversionjs="tjs.php"
flash="fl.js"
google="ag.php"
frame="afr.php"
image="ai.php"
js="ajs.php"
layer="al.php"
log="lg.php"
popup="apu.php"
view="avw.php"
xmlrpc="axmlrpc.php"
local="alocal.php"
frontcontroller="fc.php"
singlepagecall="spc.php"
spcjs="spcjs.php"
xmlrest="ax.php"

[store]
mode=0
webDir="/var/www/ads/www/images"
ftpHost=
ftpPath=
ftpUsername=
ftpPassword=
ftpPassive=

[origin]
type=
host=
port=80
script="/www/delivery/dxmlrpc.php"
timeout=10
protocol=http

[allowedBanners]
sql=1
web=1
url=1
html=1
text=1
video=1

[delivery]
cacheExpire=1200
cacheStorePlugin="deliveryCacheStore:oxCacheFile:oxCacheFile"
cachePath=
acls=1
aclsDirectSelection=1
obfuscate=
execPhp=
ctDelimiter=__
chDelimiter=","
keywords=
cgiForceStatusHeader=
clicktracking=
ecpmSelectionRate="0.9"
enableControlOnPureCPM=1

[defaultBanner]
imageUrl=

[p3p]
policies=1
compactPolicy="CUR ADM OUR NOR STA NID"
policyLocation=

[graphs]
ttfDirectory=
ttfName=

[logging]
adRequests=
adImpressions=1
adClicks=1
trackerImpressions=1
reverseLookup=
proxyLookup=1
defaultImpressionConnectionWindow=
defaultClickConnectionWindow=
ignoreHosts=
ignoreUserAgents=
enforceUserAgents=
blockAdClicksWindow=0

[maintenance]
autoMaintenance=1
timeLimitScripts=1800
operationInterval=60
blockAdImpressions=0
blockAdClicks=0
channelForecasting=
pruneCompletedCampaignsSummaryData=
pruneDataTables=1
ecpmCampaignLevels="9|8|7|6"

[priority]
instantUpdate=1
defaultClickRatio="0.005"
defaultConversionRatio="0.0001"
randmax=2147483647

[performanceStatistics]
defaultImpressionsThreshold=10000
defaultDaysIntervalThreshold=30

[table]
prefix=ox_
type=MYISAM
account_preference_assoc=account_preference_assoc
account_user_assoc=account_user_assoc
account_user_permission_assoc=account_user_permission_assoc
accounts=accounts
acls=acls
acls_channel=acls_channel
ad_category_assoc=ad_category_assoc
ad_zone_assoc=ad_zone_assoc
affiliates=affiliates
affiliates_extra=affiliates_extra
agency=agency
application_variable=application_variable
audit=audit
banners=banners
campaigns=campaigns
campaigns_trackers=campaigns_trackers
category=category
channel=channel
clients=clients
data_intermediate_ad=data_intermediate_ad
data_intermediate_ad_connection=data_intermediate_ad_connection
data_intermediate_ad_variable_value=data_intermediate_ad_variable_value
data_raw_ad_click=data_raw_ad_click
data_raw_ad_impression=data_raw_ad_impression
data_raw_ad_request=data_raw_ad_request
data_raw_tracker_impression=data_raw_tracker_impression
data_raw_tracker_variable_value=data_raw_tracker_variable_value
data_summary_ad_hourly=data_summary_ad_hourly
data_summary_ad_zone_assoc=data_summary_ad_zone_assoc
data_summary_channel_daily=data_summary_channel_daily
data_summary_zone_impression_history=data_summary_zone_impression_history
images=images
log_maintenance_forecasting=log_maintenance_forecasting
log_maintenance_priority=log_maintenance_priority
log_maintenance_statistics=log_maintenance_statistics
password_recovery=password_recovery
placement_zone_assoc=placement_zone_assoc
preferences=preferences
session=session
targetstats=targetstats
trackers=trackers
tracker_append=tracker_append
userlog=userlog
users=users
variables=variables
variable_publisher=variable_publisher
zones=zones

[email]
logOutgoing=1
headers=
qmailPatch=
fromName=
fromAddress="contact@audianz.com"
fromCompany=
useManagerDetails=

[log]
enabled=1
methodNames=
lineNumbers=
type=file
name="debug.log"
priority=6
ident=OX
paramsUsername=
paramsPassword=
fileMode=0644

[deliveryLog]
enabled=
name="delivery.log"
fileMode=0644
priority=6

[cookie]
permCookieSeconds=31536000
maxCookieSize=2048

[debug]
logfile=
production=1
sendErrorEmails=
emailSubject="Error from OpenX"
email="email@example.com"
emailAdminThreshold=3
errorOverride=1
showBacktrace=
disableSendEmails=

[var]
prefix=OA_
cookieTest=ct
cacheBuster=cb
channel=source
dest=oadest
logClick=log
n=n
params=oaparams
viewerId=OAID
viewerGeo=OAGEO
campaignId=campaignid
adId=bannerid
creativeId=cid
zoneId=zoneid
blockAd=OABLOCK
capAd=OACAP
sessionCapAd=OASCAP
blockCampaign=OACBLOCK
capCampaign=OACCAP
sessionCapCampaign=OASCCAP
blockZone=OAZBLOCK
capZone=OAZCAP
sessionCapZone=OASZCAP
vars=OAVARS
trackonly=trackonly
openads=openads
lastView=OXLIA
lastClick=OXLCA
blockLoggingClick=OXBLC
fallBack=oxfb
trace=OXTR
POI=poi
lat=lat
lon=lon
adIdKey=bannerid
POIKey=poi

[lb]
enabled=
type=mysql
host=localhost
port=3306
username=
password=
name=
persistent=

[sync]
checkForUpdates=1
shareStack=1
shareData=1

[oacSync]
protocol=https
host="sync.openx.org"
path="/xmlrpc.php"
httpPort=80
httpsPort=443

[oacXmlRpc]
protocol=https
host="oxc.openx.org"
httpPort=80
httpsPort=443
path="/oxc/xmlrpc"
captcha="/oxc/captcha"
signUpUrl="/oxc/advertiser/signup"
publihserUrl="/oxc/advertiser/defzone"

[oacDashboard]
protocol=https
host="oxc.openx.org"
port=443
path="/oxc/dashboard/home"
ssoCheck="/oxc/ssoCheck"

[authentication]
type=internal
deleteUnverifiedUsersAfter=2419200

[geotargeting]
type="geoTargeting:oxMaxMindGeoIP:oxMaxMindGeoIP"
showUnavailable=1

[pluginPaths]
packages="/plugins/etc/"
plugins="/plugins/"
admin="/www/admin/plugins/"
var="/var/plugins/"

[pluginUpdatesServer]
protocol=http
host="code.openx.org"
path="/openx/plugin-updates"
httpPort=80

[pluginSettings]
enableOnInstall=1
useMergedFunctions=1

[plugins]
openXBannerTypes=1
openXDeliveryLimitations=1
openX3rdPartyServers=1
openXReports=1
openXDeliveryCacheStore=1
openXMaxMindGeoIP=1
openXInvocationTags=1
openXDeliveryLog=1
openXMarket=0
openXWorkflow=1
openXVideoAds=1
openXDeliveryLogUnique=1
openXDeliveryLogCountry=1

[pluginGroupComponents]
oxHtml=1
oxText=1
Client=1
Geo=1
Site=1
Time=1
ox3rdPartyServers=1
oxReportsStandard=1
oxReportsAdmin=1
oxCacheFile=1
oxMemcached=1
oxMaxMindGeoIP=1
oxInvocationTags=1
oxDeliveryDataPrepare=1
oxLogClick=1
oxLogConversion=1
oxLogImpression=1
oxLogRequest=1
oxMarket=1
oxMarketMaintenance=1
oxMarketDelivery=1
openXWorkflow=1
vastInlineBannerTypeHtml=1
vastOverlayBannerTypeHtml=1
oxLogVast=1
vastServeVideoPlayer=1
videoReport=1
oxDeliveryView=1
oxLogUnique=1
oxDeliveryGeo=1
oxLogCountry=1

[audit]
enabled=1
enabledForZoneLinking=

[install]
marketPcHost="https://pc.openx.com"
marketPcApiHost="https://api.pc.openx.com"
fallbackPcApiHost="http://api.pc.openx.com"
marketPublicApiUrl="api/public/v2"
marketCaptchaUrl="https://pc.openx.com/api/captcha"
publisherSupportEmail="publisher-support@openx.org"

[Client]
sniff=1

[deliveryHooks]
postInit="deliveryLimitations:Client:initClientData"
cacheStore="deliveryCacheStore:oxCacheFile:oxCacheFile|deliveryCacheStore:oxMemcached:oxMemcached"
cacheRetrieve="deliveryCacheStore:oxCacheFile:oxCacheFile|deliveryCacheStore:oxMemcached:oxMemcached"
preLog="deliveryDataPrepare:oxDeliveryDataPrepare:dataCommon|deliveryDataPrepare:oxDeliveryDataPrepare:dataPageInfo|deliveryDataPrepare:oxDeliveryDataPrepare:dataUserAgent|deliveryDataPrepare:oxDeliveryView:dataView|deliveryDataPrepare:oxDeliveryGeo:dataGeo"
logClick="deliveryDataPrepare:oxDeliveryDataPrepare:dataCommon|deliveryDataPrepare:oxDeliveryGeo:dataGeo|deliveryDataPrepare:oxDeliveryView:dataView|deliveryLog:oxLogClick:logClick|deliveryLog:oxLogCountry:logClickCountry|deliveryLog:oxLogUnique:logClickUnique"
logConversion="deliveryLog:oxLogConversion:logConversion"
logConversionVariable="deliveryLog:oxLogConversion:logConversionVariable"
logImpression="deliveryDataPrepare:oxDeliveryDataPrepare:dataCommon|deliveryDataPrepare:oxDeliveryGeo:dataGeo|deliveryDataPrepare:oxDeliveryView:dataView|deliveryLog:oxLogCountry:logImpressionCountry|deliveryLog:oxLogImpression:logImpression|deliveryLog:oxLogUnique:logImpressionUnique"
logRequest="deliveryDataPrepare:oxDeliveryDataPrepare:dataCommon|deliveryLog:oxLogRequest:logRequest"
afterLogin="admin:oxMarket:oxMarket|admin:openXWorkflow:openXWorkflow"
registerUiListeners="admin:oxMarket:oxMarket"
objectAccess="admin:oxMarket:oxMarket"
afterAgencyCreate="admin:oxMarket:oxMarket"
addMaintenanceStatisticsTask="maintenanceStatisticsTask:oxMarketMaintenance:oxMarketMaintenance|maintenanceStatisticsTask:oxMarketMaintenance:oxMarketMaintenanceUpdateWebsites"
preAdSelect="deliveryAdRender:oxMarketDelivery:oxMarketDelivery"
postAdRender="deliveryAdRender:oxMarketDelivery:oxMarketDelivery"
postAdSelect="deliveryAdRender:oxMarketDelivery:oxMarketDelivery"
logImpressionVast="deliveryLog:oxLogVast:logImpressionVast"

[oxCacheFile]
cachePath=

[oxMemcached]
memcachedServers=
memcachedExpireTime=

[oxMaxMindGeoIP]
geoipCountryLocation=
geoipRegionLocation=
geoipCityLocation="/var/www/ads/plugins/geoTargeting/oxMaxMindGeoIP/data/FreeGeoIPCity.dat"
geoipAreaLocation=
geoipDmaLocation=
geoipOrgLocation=
geoipIspLocation=
geoipNetspeedLocation=

[oxInvocationTags]
isAllowedAdframe=true
isAllowedAdjs=true
isAllowedAdlayer=true
isAllowedAdview=true
isAllowedAdviewnocookies=true
isAllowedLocal=true
isAllowedPopup=false
isAllowedXmlrpc=false

[oxMarket]
marketHost="https://pc.openx.com"
marketPcApiHost="https://api.pc.openx.com"
fallbackPcApiHost="http://api.pc.openx.com"
marketXmlRpcUrl="api/xml-rpc"
marketPublicApiUrl="api/public/v2"
marketCustomContentUrl="market/content"
marketMenuUrl="market/index/menu"
defaultFloorPrice="0.10"
marketAccountIdParamName=pa
marketWelcomeUrl="market/index/welcome"
marketCaptchaUrl="https://pc.openx.com/api/captcha"
openXTermsUrl="http://www.openx.org/terms"
openXPrivacyUrl="http://www.openx.org/privacy"
dictionaryCacheLifeTime=86400
multipleAccountsMode=0
separateImportStatsScript=0
allowSkipAccountsInImportStats=1
maxSkippingPeriodInDays=7
adSizesPreviewUrl="http://www.openx.org/preview-ad-sizes"

[oxMarketDelivery]
brokerHost="bid.openx.net"

[openXWorkflow]
adSizesPreviewUrl="http://www.openx.org/preview-ad-sizes"
marketHost="https://pc.openx.com"
marketPcApiHost="https://api.pc.openx.com"
fallbackPcApiHost="http://api.pc.openx.com"
marketCustomContentUrl="market/content"

[vastOverlayBannerTypeHtml]
isVastOverlayAsTextEnabled=1
isVastOverlayAsSwfEnabled=1
isVastOverlayAsImageEnabled=1
isVastOverlayAsHtmlEnabled=1

[vastServeVideoPlayer]
isAutoPlayOfVideoInOpenXAdminToolEnabled=0
