var addthis_config={pubid:"itdevex",data_track_addressbar:!1,services_exclude:"email",data_ga_property:"UA-1173244-2",data_ga_social:!0},addThis=addThis||{};addThis.objectUrls=[],addThis.init=function(){$(".addthis-hover").hover(function(){$(this).find(".social-bar").show()},function(){$(this).find(".social-bar").hide()}),$(".share-count").each(function(){addThis.objectUrls.push($(this).attr("data-addthis-url"))})},addThis.displayShareCounts=function(index){$.ajax({type:"GET",dataType:"jsonp",url:"https://api-public.addthis.com/url/shares.json?url="+addThis.objectUrls[index],success:function(data){var url=addThis.objectUrls[index];data.shares>0&&$('.share-count[data-addthis-url="'+url+'"]').html(data.shares),index++,index<addThis.objectUrls.length&&addThis.displayShareCounts(index)}})},devex=devex||{},devex.jobs=devex.jobs||{},devex.jobs.detail={},devex.jobs.queryObject=function(){return JSON.parse(sessionStorage.getItem("searchQuery"))},devex.jobs.isQueryMode=function(){return devex.jobs.queryObject()&&devex.jobs.getQuery().q==devex.jobs.queryObject().urlReferer},devex.jobs.getQuery=function(){for(var url=window.location.href,qs=url.substring(url.indexOf("?")+1).split("&"),i=0,result={};i<qs.length;i++)qs[i]=qs[i].split("="),result[qs[i][0]]=qs[i][1];return result},devex.jobs.currentSlug=function(){return window.location.pathname.split("/")[2]},devex.jobs.currentSlugIndex=function(){return $.inArray(devex.jobs.currentSlug(),devex.jobs.queryObject().jobSlugs)},devex.jobs.getPrevJobSlug=function(){return devex.jobs.queryObject().jobSlugs[devex.jobs.currentSlugIndex()-1]},devex.jobs.getNextJobSlug=function(){return devex.jobs.queryObject().jobSlugs[devex.jobs.currentSlugIndex()+1]},devex.jobs.nextPage=function(){return devex.jobs.queryObject().page+1},devex.jobs.initSearchObject=function(searchObject,newQuery,newPage,newJobs){var currentJobSlugs=devex.jobs.queryObject().jobSlugs,newJobSlugs=newJobs.map(function(job){return job.slug});return{queryParams:newQuery,urlReferer:searchObject.urlReferer,page:newPage,totalPages:searchObject.totalPages,jobSlugs:$.merge(currentJobSlugs,newJobSlugs),totalJobs:searchObject.totalJobs}},devex.jobs.updateSearchObject=function(currentSearchObject,newQuery,newPage,newJobs){sessionStorage.setItem("searchQuery",JSON.stringify(devex.jobs.initSearchObject(currentSearchObject,newQuery,newPage,newJobs)))},devex.jobs.generateJobLink=function(nextPage){var slug;return slug=nextPage?devex.jobs.getNextJobSlug():devex.jobs.getPrevJobSlug(),"/jobs/"+slug+"?q="+devex.jobs.queryObject().urlReferer},devex.jobs.addSlugsToQueryObject=function(page,link){var searchUrl=devex.jobs.queryObject().queryParams;searchUrl=decodeURIComponent(searchUrl).replace(/page\[number\]=[0-9]*/,"page[number]="+page),$.get(searchUrl).done(function(data){devex.jobs.updateSearchObject(devex.jobs.queryObject(),searchUrl,page,data.data),link.attr("href",devex.jobs.generateJobLink(!0))})},$(document).ready(function(){if(devex.jobs.isQueryMode()){var prevJobLink=$(".prev-job");devex.jobs.getPrevJobSlug()?prevJobLink.attr("href",devex.jobs.generateJobLink(!1)):prevJobLink.parent("li").addClass("disabled");var nextJobLink=$(".next-job");devex.jobs.getNextJobSlug()?nextJobLink.attr("href",devex.jobs.generateJobLink(!0)):devex.jobs.queryObject().page<devex.jobs.queryObject().totalPages?devex.jobs.addSlugsToQueryObject(devex.jobs.nextPage(),nextJobLink):nextJobLink.parent("li").addClass("disabled");var paginationLabel=$(".pagination-label"),jobNumber=devex.jobs.currentSlugIndex()+1;paginationLabel.html(jobNumber+" of "+devex.jobs.queryObject().totalJobs);var customPager=$(".custom-pager");customPager.removeClass("hidden");var backToSearchLink=$(".back-to-search");backToSearchLink.attr("href",decodeURIComponent(devex.jobs.queryObject().urlReferer)),backToSearchLink.removeClass("hidden")}});