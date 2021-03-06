//EXPAND START
function toggleExpand(elem) {
  if (elem.is(":visible")) {
    elem.hide();
  } else {
    elem.show();
  }
}

jQuery(document).on("click", "[data-expand]", function() {
  var expand = jQuery(this).attr("data-expand");
  toggleExpand(jQuery("."+expand));
});

//EXPAND END

jQuery(document).on("click", ".votestar", function() {


  var movieratingdiv = jQuery(this).parents("#movierating");
  var starnr = jQuery(this).attr("data-starnr");
  var movie = jQuery(this).attr("data-movie");
  var allstars = movieratingdiv.find(".votestar");





    if (jQuery(this).hasClass("actualvote")) {
      movieratingdiv.find(".votestar").removeClass("actualvote");
      rate = "null";

      var myrating = movieratingdiv.find(".notrated");
      allstars.addClass("notrated");

    } else {

      rate = Number(starnr);
      var starclass = ".star"+rate;
      rate = rate*2;

      var myrating = movieratingdiv.find(starclass);

      movieratingdiv.find(".votestar").removeClass("actualvote");
      jQuery(this).addClass("actualvote");

      allstars.removeClass("notrated");
    }


  var obj = {
      mode: "RATEMOVIE",
	    movie: movie,
      q: rate
    };

    var returned = postAjaxPhp(obj).done(function(result) {
      //movieratingdiv.html(result);
      allstars.removeClass("myrating").removeClass("myratingdark");
      movieratingdiv.find(".votestar:not(.notrated)").addClass("myratingdark");
      myrating.addClass("myrating").removeClass("myratingdark");
    });
});

jQuery(document).on("click", ".skipmovie", function() {

	var movie = jQuery(this).attr("data-movieid");
  var holder = jQuery(this).parents("#randommoviesug").find(".contentholder");
  var rms = jQuery(this).parents("#randommoviesug");


  holder.width(holder.width());
  holder.height(holder.height());
  rms.height(holder.height());
  holder.css({position:"absolute"});
  holder.animate({left:'-100%', opacity:1}, 350, function() {
    holder.remove();
  });
	var obj = {
    mode: "PRINTRANDOMMOVIE",
    q: movie
     };
    var returned = postAjaxPhp(obj).done(function(result) {
      if (result == "") {
        rms.remove();
      }
      rms.append(result);

      rms.find(".contentholder").css({position:"absolute", opacity:1, left:"100%"}).animate({opacity:1, left:"0"}, 500, function() {

        rms.height(rms.find(".contentholder").height());
      });

    });

});

jQuery(document).on("click", ".gotologin", function() {
	window.location.href = "/login.php";
});

jQuery(document).on("click", ".newtagbtn", function() {
	jQuery("form#addtag input#tag").focus();
});

jQuery(document).on("click", ".replybutton", function() {
	var expand = jQuery(this).attr("data-expand");
	jQuery("."+expand).find("input#message").focus();
	console.log(expand);
});

jQuery(document).on("click", ".selectemoji ul.emojiboard li", function() {
	var emoji = jQuery(this).attr("data-emoji");
	var emojisrc = jQuery(this).find("img").attr("src");
	var selectedemoji = jQuery(".selectedemoji");
	selectedemoji.find("img").attr("src", emojisrc);
	selectedemoji.attr("data-emoji", emoji);
  jQuery(".selectemoji").fadeOut();
});

jQuery(document).on("click", ".selectedemoji", function() {
  showemojiselector();
});

function showemojiselector() {

  var selectemoji = jQuery(".selectemoji");
  var isactive = selectemoji.find(".emojiboard").hasClass(".emojiboard");

  if (selectemoji.is(":visible")) {
    selectemoji.fadeOut();
  } else {
    selectemoji.fadeIn();
  }

  if (!isactive) {

    var obj = {
      mode: "GETEMOJIBOARD"
      };

    var returned = postAjaxPhp(obj).done(function(result) {
      selectemoji.html(result);
    });

  }

}

function postAjaxPhp(message, page) {

	page = typeof page !== 'undefined' ? page : "ajax.php";

	return $.ajax({
    url: page,
    type: "POST",
    data: message,
    success: function(result){

    },
    error: function(){
        console.log('error');
    }
});

}

////////////////////////
//DOCUMENT READY START//
////////////////////////
jQuery(document).ready(function() {

var bestPictures = new Bloodhound({
  datumTokenizer: Bloodhound.tokenizers.obj.whitespace("title"),
  queryTokenizer: Bloodhound.tokenizers.whitespace,
  remote: {
    url: "/searchmovie.php?q=%QUERY",
    wildcard: "%QUERY"
  }
});


jQuery('.searchfield.typeahead').typeahead(null, {
  minLength: 0,
  name: "searchresults",
  display: "title",
  source: bestPictures,
	templates: {
    empty: [
      ''
    ].join('\n'),
		suggestion: function(data) {

    return '<div><a href="/movie.php?id='+data.id+'"><strong>' + data.title + '</strong> (' + data.year + ')</a></div>';
	}
},
});

jQuery(".searchfield.typeahead").bind("typeahead:selected", function(obj, data, name) {
  window.location.href = "/movie.php?id="+data.id+"";
});


var tags = new Bloodhound({
  datumTokenizer: Bloodhound.tokenizers.obj.whitespace("tag"),
  queryTokenizer: Bloodhound.tokenizers.whitespace,
  remote: {
    url: "/typeaheadtags.php?q=%QUERY",
    wildcard: "%QUERY"
  }
});


jQuery('form#addtag input#tag').typeahead(null, {
  name: "tag",
  display: "tag",
  source: tags,
	templates: {
    empty: [
      ''
    ].join('\n'),
		suggestion: function(data) {
console.log(data);
    return '<div>'+data.tag+'</div>';
	}
},
});

jQuery(document).on("click", ".quack .msgpart", function() {
  var the = jQuery(this).parents(".quack");
  if (the.hasClass("quackslim")) {
    the.removeClass("quackslim");
  } else {
    the.addClass("quackslim");
  }
});

jQuery(document).on("click", ".postmessage .submit", function(e) {

		e.preventDefault();

    var forme = jQuery(this).parents(".postmessage");
		var emoji = jQuery(".selectedemoji").attr("data-emoji");
		var message = forme.find("#message").val();
		var movie = forme.find("#movieid").val();
    var replyto = forme.parents(".quack").attr("data-postid");
    if (forme.hasClass("smileymessage")) {
      var smileymessage = true;
    }

    if ((emoji == ":bust_in_silhouette:" || emoji == "") && smileymessage) {

      showemojiselector();

    } else {


		forme.find("#message").val("");
    console.log("emoji");




		var obj = {
			mode: "POSTMESSAGE",
			emoji: emoji,
			q: message,
			movie: movie,
      replyto: replyto
			};

		var returned = postAjaxPhp(obj).done(function(result) {

      if (replyto) {
        obj = {
  			mode: "PRINTREPLIES",
  			formsg: replyto
  			};
  			postAjaxPhp(obj).done(function(result) {
  				forme.parents(".replyzone").find(".replies").html(result);
  			});
      } else {
        console.log(returned);
  			obj = {
  			mode: "PRINTMESSAGES",
  			movie: movie
  			};
  			postAjaxPhp(obj).done(function(result) {
  				jQuery(".movieposts").html(result);
  			});

      }

		});

    jQuery("#messageid").html(returned);
  }


	});


	jQuery(document).on("click", "form#addtag .submit", function(e) {
		e.preventDefault();
		var form = jQuery(this).parents("form#addtag");
		var movie = form.find("input#movieid").val();
		var tag = form.find("input#tag").val();
		obj = {
			mode: "ADDTAG",
			movie: movie,
			q: tag
		};
		postAjaxPhp(obj).done(function(result) {
			form.find("input#tag").val("");
			jQuery("span#tagscontent").html(result);
		});
	});

	jQuery(document).on("click", ".tag", function(e) {
		var movie = jQuery(this).attr("data-movie");
		var tag = jQuery(this).text();
		var active = jQuery(this).hasClass("activebtn");

		if (active) {
			var mode = "REMOVETAG";
			jQuery(this).removeClass("activebtn");
		} else {
			var mode = "ADDTAG";
			jQuery(this).addClass("activebtn");
		}
		obj = {
			mode: mode,
			movie: movie,
			q: tag
		};
		postAjaxPhp(obj).done(function(result) {
			console.log(result);
			jQuery("span#tagscontent").html(result);
		});
	});

	jQuery(document).on("click", ".voteparent .votebtn", function() {

    var voteparent = jQuery(this).parents(".voteparent");
		var votedisplay = jQuery(this).find(".votedisplay");
		var post = voteparent.attr("data-postid");
    var upvotebtn = voteparent.find(".votebtn.upvote");
    var downvotebtn = voteparent.find(".votebtn.downvote");
    var voteamount = Number(votedisplay.text());
    var isupvote = jQuery(this).hasClass("upvote");
    var upvoteactive = upvotebtn.hasClass("activebtn");
    var downvoteactive = downvotebtn.hasClass("activebtn");

		if (jQuery(this).hasClass("upvote")) {
			upvote = 1;
			downvote = 0;
		} else {
			upvote = 0;
			downvote = 1;
		}

		var obj = {
			mode: "VOTE",
			post: post,
			upvote: upvote,
			downvote: downvote
			};

      if (isupvote == true) {
        if (downvoteactive) {
         // votedisplay.text(voteamount+2);
        } else {
          //votedisplay.text(voteamount+1);
        }
        downvotebtn.removeClass("activebtn");
        if (upvoteactive) {
          upvotebtn.removeClass("activebtn");
        } else {
          upvotebtn.addClass("activebtn");
        }


      } else {
        if (upvoteactive) {
          //votedisplay.text(voteamount-2);
        } else {
          //votedisplay.text(voteamount-1);
        }
        upvotebtn.removeClass("activebtn");
        if (downvoteactive) {
          downvotebtn.removeClass("activebtn");
        } else {
          downvotebtn.addClass("activebtn");
        }

      }

    var returned = postAjaxPhp(obj).done(function(result) {

		});



	});




  jQuery(document).on("click", ".followbtn", function() {

		var follows = jQuery(this).attr("data-followedid");
    var isactive = jQuery(this).hasClass("activebtn");

		var obj = {
			mode: "FOLLOW",
			follows: follows,
			};

      if (isactive) {
        jQuery(this).removeClass("activebtn");
      } else {
        jQuery(this).addClass("activebtn");
      }
		var returned = postAjaxPhp(obj).done(function(result) {

		});

	});

  jQuery(document).on("click", ".addtolist", function() {
    var addremparent = jQuery(this).parents(".addremparent");
    var listid = jQuery(this).attr("data-list");
    var item = jQuery(this).attr("data-item");
    var obj = {
			mode: "ADDTOLIST",
			listid: listid,
      item: item
			};

		var returned = postAjaxPhp(obj).done(function(result) {
      /*var obj = {
  			mode: "GETADDTOLIST",
        q: item
  			};

  		var returned = postAjaxPhp(obj).done(function(result) {
        jQuery(".listsmenu").html(result);
      });*/
      jQuery(".addtolist[data-list='"+listid+"'][data-item='"+item+"']").addClass("activebtn removefromlist").removeClass("addtolist");
      addremparent.removeClass("removedparent");
    });
  });

  jQuery(document).on("click", ".removefromlist", function() {
    var addremparent = jQuery(this).parents(".addremparent");
    var listid = jQuery(this).attr("data-list");
    var item = jQuery(this).attr("data-item");
    var obj = {
			mode: "REMOVEFROMLIST",
			listid: listid,
      item: item
			};

		var returned = postAjaxPhp(obj).done(function(result) {
      jQuery(".removefromlist[data-list='"+listid+"'][data-item='"+item+"']").removeClass("activebtn removefromlist").addClass("addtolist");
      addremparent.addClass("removedparent");
    });
  });

  jQuery(document).on("click", ".removepost", function() {
    var addremparent = jQuery(this).parents(".addremparent");
    var item = jQuery(this).attr("data-post");
    var obj = {
			mode: "REMOVEPOST",
      q: item
			};

		var returned = postAjaxPhp(obj).done(function(result) {
      addremparent.hide();
    });
  });

  jQuery(document).on("click", ".confirmdeletelist", function() {
    var listid = jQuery(".selectedlist").val();

    var obj = {
			mode: "REMOVELIST",
			q: listid
			};

		var returned = postAjaxPhp(obj).done(function(result) {
      window.location.href = "/list.php";
    });
  });

  jQuery(document).on("click", ".createnewlist", function() {
	  var listname = jQuery("#newlistname").val();

	  var obj = {
			mode: "NEWLIST",
			listname: listname
		};

    	var returned = postAjaxPhp(obj).done(function(result) {
    		location.reload();
      });

  });



});

//////////////////////
//DOCUMENT READY END//
//////////////////////

$(function() {
  var sortableitems = $("#listitems tbody, ul.sortablelist");
  $(sortableitems).sortable({
    handle: ".handle",
    stop: function () {
      var listid = jQuery("select.selectedlist option:selected").val();
      var listOrderData = $(sortableitems).sortable('toArray');

      var obj = {
  			mode: "SORTLIST",
  			listid: listid,
			listorder: listOrderData
  		};
		console.log(listid+" "+listOrderData);

      	var returned = postAjaxPhp(obj).done(function(result) {
          console.log(result);
        });

    }
  });

});
