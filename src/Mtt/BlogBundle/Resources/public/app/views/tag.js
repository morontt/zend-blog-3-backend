/**
 * Created by morontt.
 * Date: 15.08.15
 * Time: 16:00
 */

MttBlog.TagIndexView = MttBlog.BaseView.extend({
    didInsertElement: function () {
        this._super();

        this.confirmDelete();
    }
});
