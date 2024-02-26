<style>
#notfound
{
    position:relative;
    height:100vh
}
#notfound .notfound
{
    position:absolute;
    left:50%;
    top:50%;
    -webkit-transform:translate(-50%,-50%);
    -ms-transform:translate(-50%,-50%);
    transform:translate(-50%,-50%)
}
.notfound
{
    max-width:920px;
    width:100%;
    line-height:1.4;
    text-align:center;
    padding-left:15px;
    padding-right:15px
}
.notfound .notfound-404{
    position:absolute;
    height:100px;
    top:0;
    left:50%;
    -webkit-transform:translateX(-50%);
    -ms-transform:translateX(-50%);
    transform:translateX(-50%);z-index:-1
}
.notfound .notfound-404 h1
{
    font-family:maven pro,sans-serif;
    color:#ececec;
    font-weight:900;
    font-size:276px;
    margin:0;
    position:absolute;
    left:50%;
    top:50%;
    -webkit-transform:translate(-50%,-50%);
    -ms-transform:translate(-50%,-50%);
    transform:translate(-50%,-50%)
}
.notfound h2
{
    font-family:maven pro,sans-serif;
    font-size:46px;
    color:#000;
    font-weight:900;
    text-transform:uppercase;
    margin:0
}
.notfound p{
    font-family:maven pro,sans-serif;
    font-size:16px;
    color:#000;
    font-weight:400;
    text-transform:uppercase;
    margin-top:15px
}
.notfound a
{
    font-family:maven pro,sans-serif;
    font-size:14px;
    text-decoration:none;
    text-transform:uppercase;
    background:#003473;
    display:inline-block;
    padding:16px 38px;border:2px solid transparent;
    border-radius:40px;
    color:#fff;
    font-weight:400;
    -webkit-transition:.2s all;transition:.2s all
}
.notfound a:hover
{
    background-color:#fff;
    border-color:#003473;
    color:#003473;
}
@media only screen and (max-width:480px)
{
    .notfound .notfound-404 h1
    {
        font-size:162px;
    }
    .notfound h2{
        font-size:26px;
    }
}
</style>
<div id="notfound">
    <div class="notfound">
        <div class="notfound-404">
            <h1></h1>
        </div>
        <h2>We are sorry, Form is Not accessible!</h2>
        <p>Please Allow the Permission for GeoLocation...</p>
        <p class="text-danger mt-3" id="browser_msg"></p>
        {{-- <a href="javascript:void(0);" class="geoRetryBtn">Try Again</a> --}}
        <a href="" class="RetryBtn">Try Again</a>
    </div>
</div>

<script>
    function fnBrowserDetect(){
                 
            let userAgent = navigator.userAgent;
            let browserName;
            
            if(userAgent.match(/chrome|chromium|crios/i)){
                browserName = "chrome";
            }else if(userAgent.match(/firefox|fxios/i)){
                browserName = "firefox";
            }  else if(userAgent.match(/safari/i)){
                browserName = "safari";
            }else if(userAgent.match(/opr\//i)){
                browserName = "opera";
            } else if(userAgent.match(/edg/i)){
                browserName = "edge";
            }else{
                browserName="No browser detection";
            }
            
        if(browserName=='firefox')
        {
            $('#browser_msg').html('<b>Note:- Please copy the link and open it on chrome browser for better experience.</b>');
        }         
    }

    fnBrowserDetect();
</script>
