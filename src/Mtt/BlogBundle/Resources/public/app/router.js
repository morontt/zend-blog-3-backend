/**
 * Created by morontt.
 * Date: 16.11.14
 * Time: 18:08
 */

MttBlog.Router.map(function () {
    this.resource('dashboard', { path: '/' }, function () {});
    this.resource('category', { path: '/category' }, function () {});
    this.resource('tag', { path: '/tag' }, function () {});
});
