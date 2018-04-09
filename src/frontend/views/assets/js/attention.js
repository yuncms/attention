/**
 * 发起关注
 * @param {string} model
 * @param {int} model_id
 * @param callback
 */
function attention(model, model_id, callback) {
    callback = callback || jQuery.noop;
    jQuery.post("/attention/attention/store", {model: model, model_id: model_id}, function (result) {
        return callback(result.status);
    });
}