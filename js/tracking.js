/**
* @package shorty-tracking an ownCloud url shortener plugin addition
* @category internet
* @author Christian Reiner
* @copyright 2012-2012 Christian Reiner <foss@christian-reiner.info>
* @license GNU Affero General Public license (AGPL)
* @link information
* @link repository https://svn.christian-reiner.info/svn/app/oc/shorty-tracking
*
* This library is free software; you can redistribute it and/or
* modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
* License as published by the Free Software Foundation; either
* version 3 of the license, or any later version.
*
* This library is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU AFFERO GENERAL PUBLIC LICENSE for more details.
*
* You should have received a copy of the GNU Affero General Public
* License along with this library.
* If not, see <http://www.gnu.org/licenses/>.
*
*/

/**
 * @file js/tracking.js
 * @brief Client side initialization of desktop actions
 * @access public
 * @author Christian Reiner
 */

// we use the late event $(window).load() instead of $(document).ready(), 
// since otherwise the binding of the ajax request token (CSRF protection)
// has not yet finished before we try to use it...
$(window).load(function(){
  var dfd = new $.Deferred();
  $.when(
    // load layout of dialog to show the list of tracked clicks
    Shorty.Tracking.init()
  ).done(function(){
    // bind actions to basic buttons
    Shorty.Tracking.dialogList.find('#close').on('click',function(){
      Shorty.WUI.Dialog.hide(Shorty.Tracking.dialogList);
    });
    Shorty.Tracking.dialogList.find('#list-of-clicks #titlebar').on('click',function(){
      Shorty.WUI.List.Toolbar.toggle(Shorty.Tracking.list,Shorty.WUI.List.Toolbar.toggle_callbackCheckFilter_tracking);
    });
    Shorty.Tracking.dialogList.find('#list-of-clicks #toolbar #reload').on('click',function(){Shorty.Tracking.build(false);});
    Shorty.Tracking.dialogList.find('#shorty-footer #load').on('click',function(){Shorty.Tracking.build(true);});
    // title & target filter reaction
    Shorty.Tracking.list.find('thead tr#toolbar').find('th#time,th#address,th#host,th#user').find('#filter').on('keyup',function(){
      Shorty.WUI.List.filter(
        Shorty.Tracking.list,
        $($(this).context.parentElement.parentElement).attr('id'),
        $(this).val(),
        Shorty.WUI.List.fill_callbackFilter_tracking);
    });
    // detect if the list has been scrolled to the bottom, retrieve next chunk of clicks if so
    Shorty.Tracking.list.find('tbody').on('scroll',Shorty.Tracking.bottom);
    // status filter reaction
    Shorty.Tracking.list.find('thead tr#toolbar th#result select').change(function(){
      Shorty.WUI.List.filter(
        Shorty.Tracking.list,
        $(this).parents('th').attr('id'),
        $(this).find(':selected').val(),
        Shorty.WUI.List.fill_callbackFilter_tracking);
    });
    dfd.resolve();
  }).fail(dfd.reject);
  return dfd.promise();
}); // document.ready

/**
 * @brief Method collection private to this plugin
 * @author Christian Reiner
 */
Shorty.Tracking=
{
  /**
   * @brief Persistent jQuery object holding the list dialog implemented by this plugin
   * @access private
   * @author Christian Reiner
   */
  dialogList:{},
  /**
   * @brief Persistent jQuery object holding the click dialog implemented by this plugin
   * @access private
   * @author Christian Reiner
   */
  dialogClick:{},
  /**
   * @brief Persistent referencing the Shorty this plugin currently deals with
   * @access private
   * @author Christian Reiner
   */
  entry:{},
  /**
   * @brief Persistent alphanumerical id referencing the Shorty this plugin currently deals with
   * @access private
   * @author Christian Reiner
   */
  id:{},
  /**
   * @brief Persistent jQuery object describing the list contained in this plugins dialog
   * @access private
   * @author Christian Reiner
   */
  list:{},
  /**
   * @brief Persistent jQuery object describing the list contained in this plugins dialog
   * @access private
   * @author Christian Reiner
   */
  stats:{
    blocked:[],
    denied: [],
    granted:[]
  },
  /**
   * @method Shorty.Tracking.bottom
   * @brief Decides if a scrolling event has reached the bottom of the list
   * @description If the list has been scrolled to its bottom the retrieval of the next chunk of clicks will be triggered
   * @access private
   * @author Christian Reiner
   */
  bottom: function(){
    // prevent additional events, whilst processing this one
    Shorty.Tracking.list.find('tbody').off('scroll');
    // attempt to retrieve next chunk of clicks only if it makes sense
    if(  ( ! Shorty.Tracking.dialogList.find('#shorty-footer #scrollingTurn').hasClass('disabled'))
       &&($(this).scrollTop()+$(this).innerHeight()>=$(this)[0].scrollHeight) ){
      if (Shorty.Debug) Shorty.Debug.log("list scrolled towards its bottom");
      Shorty.Tracking.build(true);
    }
    // rebind this method to the event
    Shorty.Tracking.list.find('tbody').on('scroll',Shorty.Tracking.bottom);
  }, // Shorty.Tracking.bottom
  /**
   * @method Shorty.Tracking.build
   * @brief Builds the content of the list of tracked clicks
   * @return deferred.promise
   * @access private
   * @author Christian Reiner
   */
  build: function(keep){
    keep=keep||false;
    if (Shorty.Debug) Shorty.Debug.log("building tracking list");
    var dfd = new $.Deferred();
    var fieldset=Shorty.Tracking.dialogList.find('fieldset');
    var offset=0;
    if (keep){
      if (Shorty.Debug) Shorty.Debug.log("keeping existing entries in list");
      // compute offset of next chunk to retrieve
      offset=Shorty.Tracking.list.find('tbody tr').last().attr('id');
    }else{
      if (Shorty.Debug) Shorty.Debug.log("dropping existing entries in list");
      Shorty.WUI.List.empty(Shorty.Tracking.list);
      Shorty.Tracking.dialogList.find('#shorty-footer #scrollingTurn').removeClass('disabled');
      Shorty.Tracking.list.removeClass('scrollingTable');
      Shorty.Tracking.list.find('tbody').css('height','');
    }
    $.when(
      // retrieve new entries
      Shorty.Tracking.get(Shorty.Tracking.id,offset)
    ).pipe(function(response){
      Shorty.WUI.List.fill(Shorty.Tracking.list,
                           response.data,
                           Shorty.WUI.List.fill_callbackFilter_tracking,
                           Shorty.WUI.List.add_callbackEnrich_tracking,
                           Shorty.WUI.List.add_callbackInsert_tracking);
      // updte a few general informations
      Shorty.Tracking.dialogList.find('#shorty-clicks').html(
        Shorty.Tracking.list.find('tbody tr').length+'/'+response.stats[0]['length']);
      // offer load button if there is a rest of clicks left
      if (response.rest)
           Shorty.Tracking.dialogList.find('#shorty-footer #scrollingTurn').removeClass('disabled');
      else Shorty.Tracking.dialogList.find('#shorty-footer #scrollingTurn').addClass('disabled');
    }).pipe(function(){
      $.when(
        // visualize table
        Shorty.Tracking.list.removeClass('scrollingTable'),
        Shorty.WUI.List.dim(Shorty.Tracking.list,true)
      ).done(function(){
        // decide if table needs to become scrollable
        // if so compute the right size and apply it to the body
        // this appears to be the most 'working' control
        var bodyHeight=Shorty.Tracking.dialogList.find('#list-of-clicks tbody').outerHeight(true);
        var restHeight=Shorty.Tracking.dialogList.find('fieldset legend').outerHeight(true)
                      +Shorty.Tracking.dialogList.find('#shorty-header').outerHeight(true)
                      +Shorty.Tracking.dialogList.find('#titlebar').outerHeight(true)
                      +38 // room for potentially invisible #toolbar
                      +Shorty.Tracking.dialogList.find('#shorty-footer').outerHeight(true)
                      +30;// some stuff I could not identify :-(
        var roomHeight=$('#content').outerHeight();
        // make table scrollable, when more than ... entries
        if (roomHeight<bodyHeight+restHeight)
        {
          Shorty.Tracking.list.addClass('scrollingTable');
          Shorty.Tracking.list.find('tbody').css('height',(roomHeight-restHeight-20)+'px');
        }
        // show sparkline at the right of the reference head
        Shorty.Tracking.sparkle();
        dfd.resolve();
      }).fail(dfd.reject)
    }).done(dfd.resolve).fail(dfd.reject)
    return dfd.promise();
  }, // Shorty.Tracking.build
  /**
   * @method Shorty.Tracking.control
   * @brief Central control method, called by the app to hand over control
   * @description This is the method specified as control in slot "registerActions"
   * @param entry jQuery object holding the clicked entry, in this case a row in the list of Shortys
   * @return deferred.promise
   * @access public
   * @author Christian Reiner
   */
  control:function(entry){
    if (Shorty.Debug) Shorty.Debug.log("tracking list controller");
    var dfd=new $.Deferred();
    // this is the shortys id
    Shorty.Tracking.id=entry.attr('id');
    Shorty.Tracking.entry=entry;
    // update lists reference bar content to improve intuitivity
    Shorty.Tracking.dialogList.find('#shorty-title').html(entry.attr('data-title'));
    Shorty.Tracking.dialogList.find('#shorty-status').html(entry.attr('data-status'));
    Shorty.Tracking.dialogList.find('#shorty-until').html(
      ((!entry.attr('data-until')) ? "-"+t('shorty',"never")+"-" : entry.attr('data-until')) );
    var clicks=Shorty.Tracking.dialogList.find('#shorty-header #clicks');
    clicks.html(clicks.attr('data-slogan')+': '+entry.attr('data-clicks'));
    // prepare to (re-)fill the list
    $.when(
      Shorty.WUI.List.empty(Shorty.Tracking.dialogList),
      Shorty.Tracking.dialogList.find('#shorty-footer #scrollingTurn').removeClass('disabled')
    ).done(function(){
      Shorty.WUI.Dialog.show(Shorty.Tracking.dialogList)
      dfd.resolve();
    }).fail(function(){
      dfd.reject();
    })
    // load first content into the list
    Shorty.Tracking.build();
    return dfd.promise();
  }, // Shorty.Tracking.control
  /**
   * @method Shorty.Tracking.details
   * @brief Visualizes clicks details inside a popup
   * @access private
   * @author Christian Reiner
   */
  details:function(element){
    if (Shorty.Debug) Shorty.Debug.log("visualizing details on click '"+element.attr('id')+"' in tracking list");
    var dfd = new $.Deferred();
    // use the existing 'share' dialog for this
    var entry =Shorty.Tracking.entry;
    var dialog=$('#shorty-tracking-click-dialog');
    // fill and dialog
    $.each(['title'],function(i,item){
      switch(item){
        default:
          dialog.find('#shorty-'+item).text(entry.attr('data-'+item))
                                      .attr('data-'+item,entry.attr('data-'+item));
      } // switch
    })
    $.each(['result','address','host','user','time'],function(i,item){
      switch(item){
        case 'result':
          dialog.find('#click-'+item).text(t('shorty-tracking',element.attr('data-'+item)))
                                     .attr('data-'+item,element.attr('data-'+item));
          break;
        case 'time':
          dialog.find('#click-'+item).text(formatDate(1000*element.attr('data-'+item)))
                                     .attr('data-'+item,element.attr('data-'+item));
          break;
        default:
          dialog.find('#click-'+item).text(element.attr('data-'+item))
                                     .attr('data-'+item,element.attr('data-'+item));
      } // switch
    })
    // move 'share' dialog towards entry
    dialog.appendTo(element.find('td#actions'));
    // open dialog
    $.when(
      Shorty.WUI.Dialog.show(dialog)
    ).done(dfd.resolve)
    return dfd.promise();
  }, // Shorty.Tracking.details
  /**
   * @method Shorty.Tracking.get
   * @brief Fetches a list of all registered clicks matching a specified Shorty
   * @param shorty string Id of the Shorty the click list is requested for
   * @param offset Numeric id of the last click that is already present in the list (ids being in chronological order!)
   * @return deferred.promise
   * @access private
   * @author Christian Reiner
   */
  get:function(shorty,offset){
    if (Shorty.Debug) Shorty.Debug.log("loading clicks into tracking list");
    // no offset specified ? then start at the beginning
    offset = offset || 0;
    var dfd=new $.Deferred();
    // retrieve list template
    var data={shorty:shorty,offset:offset};
    $.ajax({
      type:     'GET',
      url:      OC.filePath('shorty-tracking','ajax','get.php'),
      cache:    false,
      data:     data,
      dataType: 'json'
    }).pipe(
      function(response){return Shorty.Ajax.eval(response)},
      function(response){return Shorty.Ajax.fail(response)}
    ).done(function(response){
      dfd.resolve(response);
    }).fail(function(response){
      dfd.reject(response);
    })
    return dfd.promise();
  }, // Shorty.Tracking.get
  /**
   * method Shorty.Tracking.init
   * @brief Initializes the dialog this aplugin adds to the Shorty app
   * @description The html content of the dialog is fetched via ajax
   * @return deferred.promise
   * @access public
   * @author Christian Reiner
   */
  init:function(){
    if (Shorty.Debug) Shorty.Debug.log("initializing tracking list");
    var dfd=new $.Deferred();
    // two dialogs are used by this plugin
    var dialogs={
      'list':  Shorty.Tracking.dialogList,
      'click': Shorty.Tracking.dialogClick
    };
    // load dialogs from server
    $.each(['list','click'],function(i,dialog){
      // does the dialog holding the list exist already ?
      if (!$.isEmptyObject(dialogs[dialog])){
        // remove all (real) entries so that the table can be filled again
        if ('list'==dialog)
          $('#shorty-tracking-list-dialog #list-of-clicks tr:not(#)').remove();
        dfd.resolve();
      }else{
        // load dialog layout via ajax and create a freshly populated dialog
        $.ajax({
          type:     'GET',
          url:      OC.filePath('shorty-tracking','ajax','layout.php'),
          data:     { dialog: dialog},
          cache:    false,
          dataType: 'json'
        }).pipe(
          function(response){return Shorty.Ajax.eval(response)},
          function(response){return Shorty.Ajax.fail(response)}
        ).done(function(response){
          // create a fresh dialog and insert it alongside the existing dialogs in the top controls bar
          $('#controls').append(response.layout);
          switch (dialog){
            case 'list':
              Shorty.Tracking.dialogList=$('#controls #shorty-tracking-list-dialog').first();
              Shorty.Tracking.list=Shorty.Tracking.dialogList.find('#list-of-clicks').first();
              break;
            case 'click':
              Shorty.Tracking.dialogClick=$('#controls #shorty-tracking-click-dialog').first();
          } // switch
          dfd.resolve();
        }).fail(dfd.reject)
      } // else
    }); // each
    return dfd.promise();
  },
  /**
   * @method Shorty.Tracking.sparkle
   * @brief Creates a 'click sparkline' at the top right of the dialog
   * @author Christian Reiner
   */
  sparkle:function(){
    var sparkline=Shorty.Tracking.dialogList.find('#stats').first();
    // set range of sparkline as [Shorty-creation...now]
    var rangeMin=Math.floor($.datepicker.formatDate('@',new Date(Shorty.Tracking.entry.attr('data-created')))/1000);
    var rangeMax=Math.ceil(0.5+$.datepicker.formatDate('@',new Date())/1000);
    var range   =rangeMax-rangeMin;
    // we need to compute a value notation the jquery sparkline extension understands:
    // []
    var granted=new Array();
    var denied =new Array();
    var blocked=new Array();
    var column, steps=20;
    // initialize all columns as zero value
    for (column=0;column<=steps;column=column+1){granted[column]=0;denied[column]=0;blocked[column]=0;}
    // increment matching range column for each click
    $.each(Shorty.Tracking.stats.granted,function(i,time){granted[Math.round((time-rangeMin)/(range/steps))]++;});
    $.each(Shorty.Tracking.stats.denied, function(i,time){ denied[Math.round((time-rangeMin)/(range/steps))]++;});
    $.each(Shorty.Tracking.stats.blocked,function(i,time){blocked[Math.round((time-rangeMin)/(range/steps))]++;});
    // initialize stats sparkline
    $(stats).sparkline(granted,{width:'160px',height:'1.6em',type:'line',lineColor:'green' ,fillColor:false} );
    $(stats).sparkline(denied, {width:'160px',height:'1.6em',type:'line',lineColor:'yellow',fillColor:false,composite:true} );
    $(stats).sparkline(blocked,{width:'160px',height:'1.6em',type:'line',lineColor:'red',   fillColor:false,composite:true} );
    $(stats).off('sparklineRegionChange');
    $(stats).on('sparklineRegionChange', function(ev) {
      var sparkline = ev.sparklines[0],
      region = sparkline.getCurrentRegionFields();
      value = region.y;
      $('.mouseoverregion').text("x="+region.x+" y="+region.y);
    }).bind('mouseleave', function() {
      $('.mouseoverregion').text('');
    });
  } // Shorty.Tracking.sparkle
} // Shorty.Tracking

/**
 * @method Shorty.WUI.List.add_callbackEnrich_tracking
 * @brief Callback function replacing the default used in Shorty.WUI.List.add()
 * @param row jQuery object Holding a raw clone of the 'dummy' entry in the list, meant to be populated by real values
 * @param set object This is the set of attributes describing a single registered click
 * @param hidden bool Indicats if new entries in lists should be held back for later highlighting (flashing) optically or not
 * @description This replacement uses the plugin specific column names
 * @access public
 * @author Christian Reiner
 */
Shorty.WUI.List.add_callbackEnrich_tracking=function(row,set,hidden){
  // set row id to entry id
  row.attr('id',set.id);
  // hold back rows for later highlighting effect
  if (hidden)
    row.addClass('shorty-fresh'); // might lead to a pulsate effect later
  // add aspects as content to the rows cells
  $.each(['status','time','address','host','user','result'],
         function(j,aspect){
    // we wrap the cells content into a span tag
    var span=$('<span>');
    // enhance row with real set values
    if ('undefined'==set[aspect])
         row.attr('data-'+this,'');
    else row.attr('data-'+this,set[aspect]);
    // fill data into corresponsing column
    var title, content, classes=[];
    switch(aspect){
      case 'status':
        var icon;
        switch (set['result']){
          case 'blocked': icon='bad';     break;
          case 'denied':  icon='neutral'; break;
          case 'granted': icon='good';    break;
          default:        icon='blank';
        } // switch
        span.html('<img class="shorty-icon" width="16" src="'+OC.filePath('shorty','img/status',icon+'.png')+'">');
        break;
      case 'time':
        if (null==set[aspect])
             span.text('-?-');
        else span.text(formatDate(1000*set[aspect]));
        // add value to the sparkline value set in the header
        switch (set['result']){
          case 'blocked': Shorty.Tracking.stats.blocked.push(set[aspect]); break;
          case 'denied':  Shorty.Tracking.stats.denied.push (set[aspect]); break;
          case 'granted': Shorty.Tracking.stats.granted.push(set[aspect]); break;
        } // switch
        break;
      case 'result':
        span.text(t('shorty-tracking',set[aspect]));
        span.addClass('ellipsis');
        break;
      default:
        span.text(set[aspect]);
        span.addClass('ellipsis');
    } // switch
    row.find('td#'+aspect).empty().append(span);
  }) // each aspect
} // Shorty.WUI.List.add_callbackEnrich_tracking
/**
 * @method Shorty.WUI.List.add_callbackInsert_tracking
 * @brief Inserts a cloned and enriched row into the table at a usage specific place
 * @description New entries always get appended to the list of already existing entries,
 *              since those are always sorted in a chronological order
 * @access public
 * @author Christian Reiner
 */
Shorty.WUI.List.add_callbackInsert_tracking=function(list,row){
  list.find('tbody').append(row);
} // Shorty.WUI.List.add_callbackInsert_tracking

/**
 * @method Shorty.WUI.List.fill_callbackFilter_tracking
 * @brief Column filter rules specific to this plugins list
 * @access public
 * @author Christian Reiner
 */
Shorty.WUI.List.fill_callbackFilter_tracking=function(list){
  if (Shorty.Debug) Shorty.Debug.log("using 'tracking' method to filter filled list");
  // filter list
  Shorty.WUI.List.filter(list,'time',   list.find('thead tr#toolbar th#time    #filter').val()),
  Shorty.WUI.List.filter(list,'address',list.find('thead tr#toolbar th#address #filter').val()),
  Shorty.WUI.List.filter(list,'host',   list.find('thead tr#toolbar th#host    #filter').val()),
  Shorty.WUI.List.filter(list,'user',   list.find('thead tr#toolbar th#user    #filter').val()),
  Shorty.WUI.List.filter(list,'result', list.find('thead tr#toolbar th#result  select :selected').val())
} // Shorty.WUI.List.fill_callbackFilter_tracking

/**
 * @method Shorty.WUI.List.Toolbar.toggle_callbackCheckFilter_tracking
 * @brief Callback used to check if any filters prevent closing a lists toolbar
 * @param toolbar jQueryObject The lists toolbar filters should be checked in
 * @return bool Indicates if an existing filter prevents the closing or not
 * @description Used as a replacement for the default used in Shorty.WUI.List.Toolbar.toggle()
 * This version is private to this plugin and uses the filter names specific to the list of tracked clicks
 * @access public
 * @author Christian Reiner
 */
Shorty.WUI.List.Toolbar.toggle_callbackCheckFilter_tracking=function(toolbar){
  return (  (  (toolbar.find('th#time,#address,#host,#user').find('div input#filter:[value!=""]').length)
             &&(toolbar.find('th#time,#address,#host,#user').find('div input#filter:[value!=""]').effect('pulsate')) )
          ||(  (toolbar.find('th#result select :selected').val())
             &&(toolbar.find('#result').effect('pulsate')) ) );
} // Shorty.WUI.List.Toolbar.toggle_callbackCheckFilter_tracking
