(function($) {
    $.fn.setCheckboxesShift = function() {
        var cbx = this,
        last = -1,     // номер предыдущего чекбокса;
        start,         // служебные
        finish;     // переменные
    
        // проходим по каждому указанному чекбоксу
        cbx.each(function(index) {
            // при клике по нему (или его лэйблу)
            $(this).click(function(e) {
                // проверяем, стал ли он чекнутым
                if($(e.target).attr('checked')) {
                    // теперь смотрим, больше ли у предыдущего индекс
                    if(index > last) {     start     = last;     finish     = index;
                    } else {             start     = index;     finish     = last; }
                
                    // если был предыдущий и зажата клавиша Шифт
                    if(last > -1 && e.shiftKey) {    
                        // проходим с начального до конечного чекбокса        
                        for(i = start; i <= finish; i ++) {
                            // и делаем их выделенными
                            $(cbx[i]).attr('checked', 'checked');
                        }
                    }
                    // запоминаем индекс только что кликнутого чекбокса
                    last = index;
                }
            });
        });
    };
})(jQuery);