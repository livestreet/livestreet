var clientPC = navigator.userAgent.toLowerCase();
var clientVer = parseInt(navigator.appVersion);
var is_ie = ((clientPC.indexOf("msie") != -1) && (clientPC.indexOf("opera") == -1));
var is_nav = ((clientPC.indexOf('mozilla')!=-1) && (clientPC.indexOf('spoofer')==-1)
                && (clientPC.indexOf('compatible') == -1) && (clientPC.indexOf('opera')==-1)
                && (clientPC.indexOf('webtv')==-1) && (clientPC.indexOf('hotjava')==-1));
var is_moz = 0;

var is_win = ((clientPC.indexOf("win")!=-1) || (clientPC.indexOf("16bit") != -1));
var is_mac = (clientPC.indexOf("mac")!=-1);

function voidPutURL(context)
{       if (url=prompt('Введите ссылку','http://'))
        {
        var m=document.getElementById(context);
        if(m){
               m.focus();

             if ((clientVer >= 4) && is_ie && is_win)
             {
               sel = document.selection.createRange();

               sel.text = "<a href=\""+url+"\">"+sel.text+'</a>';
             } else
             {
               mozWrap(m, "<a href=\""+url+"\">",'</a>');
             }
             m.focus();
             }
         }
}

function voidPutB(context)
{
        var m=document.getElementById(context);
        if(m){
               m.focus();
             if ((clientVer >= 4) && is_ie && is_win)
             {
               sel = document.selection.createRange();

               sel.text = "<b>"+sel.text+'</b>';
             } else
             {
               mozWrap(m, "<b>", '</b>');
             }
             m.focus();
             }
}

function voidPutI(context)
{
        var m=document.getElementById(context);
        if(m){
               m.focus();
             if ((clientVer >= 4) && is_ie && is_win)
             {
               sel = document.selection.createRange();

               sel.text = "<i>"+sel.text+'</i>';
             } else
             {
               mozWrap(m, "<i>", '</i>');
             }
             m.focus();
             }
}

function voidPutU(context)
{
        var m=document.getElementById(context);
        if(m){
               m.focus();
             if ((clientVer >= 4) && is_ie && is_win)
             {
               sel = document.selection.createRange();

               sel.text = "<u>"+sel.text+'</u>';
             } else
             {
               mozWrap(m, "<u>", '</u>');
             }
             m.focus();
             }
}

function voidPutS(context)
{
        var m=document.getElementById(context);
        if(m){
               m.focus();
             if ((clientVer >= 4) && is_ie && is_win)
             {
               sel = document.selection.createRange();

               sel.text = "<s>"+sel.text+'</s>';
             } else
             {
               mozWrap(m, "<s>", '</s>');
             }
             m.focus();
             }
}

function voidPutCode(context)
{
        var m=document.getElementById(context);
        if(m){
               m.focus();
             if ((clientVer >= 4) && is_ie && is_win)
             {
               sel = document.selection.createRange();

               sel.text = "<code>"+sel.text+'</code>';
             } else
             {
               mozWrap(m, "<code>", '</code>');
             }
             m.focus();
             }
}

function voidPutTag2(context,tag)
{
        var m=document.getElementById(context);
        if(m){
               m.focus();
             if ((clientVer >= 4) && is_ie && is_win)
             {
               sel = document.selection.createRange();

               sel.text = "<"+tag+">"+sel.text+'</'+tag+'>';
             } else
             {
               mozWrap(m, "<"+tag+">", '</'+tag+'>');
             }
             m.focus();
             }
}

function voidPutTag(context,tag){
        var m=document.getElementById(context);
        if(m){

                if(document.selection){
                        m.focus();
                        sel=document.selection.createRange();
                        sel.text=tag;
                }
                else if(m.selectionStart || m.selectionStart=="0") {
                        var s=m.selectionStart;
                        var e=m.selectionEnd;
                        m.value=m.value.substring(0,s)+tag+m.value.substring(e,m.value.length);
                }else{
                        m.value += tag;
                }
                m.focus();
        }
}

function mozWrap(txtarea, open, close)
{
        var selLength = txtarea.textLength;
        var selStart = txtarea.selectionStart;
        var selEnd = txtarea.selectionEnd;
        if (selEnd == 1 || selEnd == 2)
                selEnd = selLength;

        var s1 = (txtarea.value).substring(0,selStart);
        var s2 = (txtarea.value).substring(selStart, selEnd)
        var s3 = (txtarea.value).substring(selEnd, selLength);
        if (s2!='')
         {
          txtarea.value = s1 + open + s2 + close + s3;
         }
        return;
}