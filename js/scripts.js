/**
 * Created by user on 20.04.2016.
 * Contains common functions that may be useful in any javascript scenario.
 */
function chObj(object){
    if (!object) {
        return {};
    }
    return object;
}
function chArr(arr){
    if (!(arr instanceof Array)) {
        return [];
    }
    return arr;
}
/**
 * @return string - a unique identificator
 */
function generateId(){
    var d = new Date();
    var rand = getRandomArbitary(100,999);
    return 'id'+ d.getTime() + rand;
}
function getRandomArbitary(min, max) {
    return Math.random() * (max - min) + min;
}
/**
 *  Удаление элемента из массива.
 *  @param value: значение, которое необходимо найти и удалить.
 *  @return  массив без удаленного элемента; false в противном случае.
 */
Array.prototype.remove = function(value) {
    var idx = this.indexOf(value);
    if (idx != -1) {
        // Второй параметр - число элементов, которые необходимо удалить
        this.splice(idx, 1);
    }
    return this;
}