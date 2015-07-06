<span style="float: right;">
  {{nextPage:if}}
    <a href="{{nextPageURL}}" class="btn default"><i class="fa fa-chevron-left"></i> Next</a>
  {{end}}
  {{nextPage:ifNot}}
    <a class="btn default disabled"><i class="fa fa-chevron-left"></i> Next</a>
  {{end}}
  {{prevPage:if}}
    <a href="{{prevPageURL}}" class="btn default">Previous <i class="fa fa-chevron-right"></i></a>
  {{end}}
  {{prevPage:ifNot}}
    <a class="btn default disabled">Previous <i class="fa fa-chevron-right"></i></a>
  {{end}}
</span>
<a href="#" class="btn default"><i class="fa fa-cog"></i> Settings</a>
<a href="#" class="btn default"><i class="fa fa-save"></i> Save Position</a>
<a class="btn default disabled"><i class="fa fa-folder-open-o"></i> Continue</a>
