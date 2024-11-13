import DS from 'ember-data';
import net from 'mtt-blog/utils/ip2long';

/**
 * Following RFC 1918, Section 3
 *
 * 10.0.0.0    - 10.255.255.255  (10/8 prefix)
 * 172.16.0.0  - 172.31.255.255  (172.16/12 prefix)
 * 192.168.0.0 - 192.168.255.255 (192.168/16 prefix)
 */
const cidrs = [
    {ip: '10.0.0.0', mask: 8},
    {ip: '172.16.0.0', mask: 12},
    {ip: '192.168.0.0', mask: 16},
];

export default DS.Model.extend({
    statusCode: DS.attr('number'),
    ipAddr: DS.attr('string'),
    city: DS.attr('string'),
    region: DS.attr('string'),
    country: DS.attr('string'),
    countryFlag: DS.attr('string'),
    requestUri: DS.attr('string'),
    userAgent: DS.belongsTo('userAgent'),
    articleTitle: DS.attr('string'),
    articleSlug: DS.attr('string'),
    isCDN: DS.attr('boolean'),
    duration: DS.attr('string'),
    method: DS.attr('string'),
    createdAt: DS.attr('date'),
    privateIP: function () {
        let ipLong = net.ip2long(this.get('ipAddr'));
        let isPrivate = false;

        return cidrs.reduce(function (accumulator, current) {
            return accumulator || (ipLong >> (32 - current.mask)) === (net.ip2long(current.ip) >> (32 - current.mask));
        }, isPrivate);
    }.property('ipAddr'),
});
