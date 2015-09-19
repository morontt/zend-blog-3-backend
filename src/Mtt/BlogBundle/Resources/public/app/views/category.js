/**
 * Created by morontt.
 * Date: 10.05.15
 * Time: 2:50
 */

MttBlog.CategoryIndexView = MttBlog.BaseView.extend({
    didInsertElement: function () {
        this._super();

        $.ajax({
            url: Routing.generate('mtt_blog_default_ajaxcategory'),
            success: function (data) {
                var options = ['<option value="">...</option>'];
                data.forEach(function (val) {
                    options.push('<option value="' + val.id + '">' + val.name + '</option>');
                });

                $('#category_parent').html(options.join(''));
            }
        });

        this.confirmDelete();
    }
});
