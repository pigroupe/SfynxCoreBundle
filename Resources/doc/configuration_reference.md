#Configuration Reference

All available configuration options are listed below with their default values.

``` yaml
#
# SfynxCoreBundle configuration
#       
sfynx_core:  
    cache_dir:
        media: "%kernel.root_dir%/cachesfynx/Media/" 
    cookies:
        date_expire: true
        date_interval:  %pi_cookie_lifetime% # 604800 PT4H  604800
        application_id: sfynx  
    translation:
        defaultlocale_setting: false        
    permission:
        restriction_by_roles: false
        authorization:
            prepersist: true
            preupdate: true
            preremove: true
        prohibition:
            preupdate: true
            preremove: true  
```
