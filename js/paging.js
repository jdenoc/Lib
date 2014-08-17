/**
 * Created by denis on 7/18/14.
 */

var paging = {
    limit: 50,
    totalElements: -1,
    nextObj: {},
    prevObj: {},
    current: 0,
    next: function(){
        paging.current++;
        paging.prevObj.show().val(paging.current-1);
        paging.nextObj.val( paging.current+1 );
        if((paging.current+1)*paging.limit >= paging.totalElements){
            paging.nextObj.hide();
        }
    },
    prev: function(){
        paging.current--;
        paging.prevObj.val( paging.current-1 );
        paging.nextObj.show().val( paging.current );
        if(paging.current <= 0){
            paging.prevObj.hide();
            paging.current = 0;
        }
    },
    reset: function(){
        if(paging.totalElements == -1){
            setTimeout(paging.reset, 500);
        } else {
            paging.current=0;
            paging.prevObj.val(0).hide();
            paging.nextObj.val( paging.current+1);
            if((paging.current+1)*paging.limit >= paging.totalElements){
                paging.nextObj.hide();
            } else {
                paging.nextObj.show();
            }
        }
    }
};