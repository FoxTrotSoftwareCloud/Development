<html>
<head>
 <title>Test Page for DSTHTTPDL</title>
</head>
<script language="javascript">
function GeFileList()
{
 HTTPDL_result.value = "";
 HTTPDL.Host = "filetransfer.financialtrans.com";
 HTTPDL.UseProxy = false;
 HTTPDL.LocalDirectory = “c:\temp”;
 HTTPDL.UseHttps = true;
 <!—- Note: Test System Information -->
 HTTPDL.Target = "/tf/FANMail-Test";
 HTTPDL.Client = "419041819";
 <!—- Note: For testing UserID and Password will be supplied by DST -->
 HTTPDL.UserID = UserID.value;
 HTTPDL.Password = Password.value;
 var list = HTTPDL.GetFileListAsXML();
 var dlist = "No file list returned";
 var xmldoc = MSXML3;
 xmldoc.async = false;
 xmldoc.preserveWhiteSpace = true;
 xmldoc.loadXML(list);
 var docelement = xmldoc.documentElement;
 if ( docelement.hasChildNodes() )
 {
 dlist = "<form name=\"Selection\">";
 var nodeList = docelement.childNodes;
 var node = nodeList.nextNode();
 while ( node != null )
 {
 var file = node.getAttribute("name");
 var display = node.getAttribute("short-name");
 dlist += "<input type=\"checkbox\" name=\"sfile\" value=\"";
 dlist += file;
 dlist += "\">&nbsp;";
 dlist += display;
 dlist += "<br>";
 node = nodeList.nextNode();
Internet Dealer Commissions User Guide
- 54 -
 }
 dlist += "<br>";
 dlist += "<input type=\"button\" value=\"Download Files\"
onclick=\"Download()\">";
 dlist += "&nbsp;<input type=\"button\" value=\"Cancel\"
onclick=\"CancelDownload()\">";
 dlist += "<br>";
 dlist += "</form>";
 }
 FileList.innerHTML = dlist;
}
function Download()
{
 HTTPDL.LocalDirectory = DestDir.value;
 var selection = document.forms[0].sfile;
 var flist = "";
 for ( index=0; index<selection.length; ++index)
 {
 if ( selection[index].checked )
 {
 flist += selection[index].value;
 flist += ";";
 }
 }
 PostResult( "Begin Download" );
 HTTPDL.DownloadFiles( flist );
}
function CancelDownload()
{
 HTTPDL.CancelRequest();
}
function TerminateDownload()
{
 HTTPDL.Terminate();
}
function PostResult( msg )
{
 content = HTTPDL_result.value;
 content += msg;
 content += "\r\n";
 HTTPDL_result.value = content;
}
</script>
<script for="HTTPDL" event="DownloadError( code, msg )" language="javascript">
PostResult( msg );
Internet Dealer Commissions User Guide
- 55 -
</script>
<script for="HTTPDL" event="DownloadComplete()" language="javascript">
PostResult( "DownloadComplete" );
</script>
<script for="HTTPDL" event="DownloadProgress( msg )" language="javascript">
PostResult( msg );
</script>
<body onunload="TerminateDownload()">
<object id="HTTPDL" height=0 width=0
 classid="CLSID:2DEA82A9-7FEF-4F68-8091-B800ECF54C9F"
 codeBase="http:DSTHTTPDL.cab#version=1,0,0,2">
</object>
<object id="MSXML3" style="DISPLAY: none"
 codeBase="http:msxml3.cab#version=8,00,7820,0"
 type="application/x-oleobject"
 data="data:application/x-oleobject;base64,EQ/Z9nOc0xGzLgDAT5kLtA=="
 classid="clsid:f5078f32-c551-11d3-89b9-0000f81fe221">
</object>
<table>
<tr>
 <td style="padding-bottom:20px;vertical-align:top">
 <div id="Main">
 <p>User ID: <input type="text" id="UserID" size=10>&nbsp;&nbsp;
 Password: <input type="password" id="Password" size=10></P>
 <p><input type="button" value="Get FileList" onclick="GeFileList()"></p>
 </div>
</tr>
<tr>
 <td>
 <textarea name="HTTPDL_result" rows=10 cols=50 wrap=soft></textarea>
 </td>
</tr>
<tr>
 <td style="padding-top:20px;vertical-align:top">
 <p>Destination Directory (local):
 <input type="text" id="DestDir" size=30></p>
 </td>
</tr>
<tr>
 </td>
 <td style="padding-left:20px;padding-top:20px;">
 <div id="FileList">
 </div>
 </td>
</tr>
</table>
</body>
</html>