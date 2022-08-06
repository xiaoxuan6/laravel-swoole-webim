var config = {
    'domain': "http://127.0.0.1:9090",
    'wsserver': "ws://127.0.0.1:9090",
}

var initConfiguare = {
    /**
     * 当一个用户进来或者刷新页面触发本方法
     *
     */
    initPage: function (data) {
        this.initRooms(data.rooms);
        this.initUsers(data.users);
    },

    /**
     * 1.初始化房间
     * 2.初始化每个房间的用户列表
     * 3.初始化每个房间的聊天列表
     */
    initRooms: function (data) {
        var rooms = [];//房间列表
        var userlists = [];//用户列表
        var chatlists = [];//聊天列表
        if (data.length) {
            var display = 'none';
            for (var i = 0; i < data.length; i++) {
                if (data[i]) {
                    //存储所有房间ID
                    chat.data.rds.push(data[i].roomid);
                    data[i].selected = '';
                    if (i == 0) {
                        data[i].selected = 'selected';
                        chat.data.crd = data[i].roomid; //存储第一间房间ID，自动设为默认房间ID
                        display = 'block';//第一间房的用户列表和聊天记录公开
                    }
                    //初始化每个房间的用户列表
                    userlists.push(cdiv.userlists(data[i].roomid, display));
                    //初始化每个房间的聊天列表
                    chatlists.push(cdiv.chatlists(data[i].roomid, display));
                    //创建所有的房间
                    rooms.push(cdiv.render('rooms', data[i]));
                    display = 'none';
                }
            }
            $('.main-menus').html(rooms.join(''));
            $("#user-lists").html(userlists.join(''));
            $("#chat-lists").html(chatlists.join(''));
        }
    },
    /**
     * 填充房间用户列表
     */
    initUsers: function (data) {
        if (getJsonLength(data)) {
            for (var item in data) {
                var users = [];
                var len = data[item].length;
                if (len) {
                    for (var i = 0; i < len; i++) {
                        if (data[item][i]) {
                            users.push(cdiv.render('user', data[item][i]));
                        }
                    }
                }
                $('#conv-lists-' + item).html(users.join(''));
            }
        }
    },
}
