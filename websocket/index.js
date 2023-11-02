const express = require('express');
const moment = require('moment-timezone');
const app = express();
const http = require('http');
const server = http.createServer(app);
const { Server } = require("socket.io");
const io = new Server(server, {
    cors: {
        origin: "https://iqua.atrest.xyz",
        methods: ["GET", "POST"]
    }
});

var mysql = require('mysql2');

var con = mysql.createConnection({
    host: "localhost",
    user: "root",
    password: "",
    database: "iqua"
});

con.connect(function (err) {
    if (err) throw err;
    console.log("Connected to the database!");
});

const mqtt = require("mqtt");
const mqtt_client = mqtt.connect("mqtt://140.117.172.18:9183", { username: "libralien", password: "LibrAlien2023jiayou" });

let seq = 0;

io.on('connection', (socket) => {
    console.log('a user connected', socket.id);
    socket.on('set_room_id', (msg) => {
        socket.join(msg);
        console.log('set_room_id: ' + msg);
    });

    socket.on('action', (msg) => {
        const UTCTime = new Date().toISOString();
        console.log('action: ' + JSON.stringify(msg));
        msg.event.seq = seq++;
        // console.log(msg.event);
        let data = {
            "type": "action",
            "event": msg.event,
            "value": msg.value
        }

        let sql = "SELECT * FROM devices WHERE serial_number = ? LIMIT 1;";
        let values = [msg.device];

        con.query(sql, values, function (err, deviceResult) {
            if (err) throw err;
            if (deviceResult.length == 0) return;
            const device_id = deviceResult[0].device_id;
            const group_id = deviceResult[0].group_id;
            const device_type_id = deviceResult[0].device_type_id;
            // const options = JSON.parse(deviceResult[0].options);
            // console.log(deviceResult)

            let sql = "SELECT * FROM groups WHERE id = ? LIMIT 1";
            let values = [group_id];

            con.query(sql, values, function (err, groupResult) {
                if (err) throw err;
                if (groupResult.length == 0) return;
                const groupUuid = groupResult[0].uuid;

                let sql = "SELECT * FROM entities WHERE name = ? AND device_type_id = ? LIMIT 1";
                let values = [data.event, device_type_id];
                con.query(sql, values, function (err, entityResult) {
                    if (err) throw err;
                    if (deviceResult.length == 0) return;
                    console.log(entityResult);

                    let eventData = {
                        device: msg.device,
                        initiator: "user",
                        type: "event",
                        event: data.event,
                        // value: options[data.value],
                        time: moment(UTCTime).tz(groupResult[0].timezone).format("DD/MM/YYYY HH:mm:ss")
                    };

                    let options = entityResult[0].options;
                    if (options !== null) {
                        options = JSON.parse(options);
                        eventData.value = options[data.value];
                    } else {
                        eventData.value = mqtt_data.value;
                    }

                    io.to(groupUuid).emit('event', eventData);

                    let sqlEvent = "INSERT INTO `device_events` (`initiator`, `device_id`, `group_id`, `type`, `event`, `value`, `created_at`) VALUES (?, ?, ?, ?, ?, ?, ?)";
                    let valuesEvent = ["user", device_id, group_id, eventData.type, data.event, data.value, UTCTime];
                    con.query(sqlEvent, valuesEvent, function (err, result) {
                        if (err) throw err;
                        console.log(result)

                        mqtt_client.publish(`libralien/${msg.device}/action`, JSON.stringify(data));
                    });

                });
            });
        });
    });
});

mqtt_client.on("connect", () => {
    mqtt_client.subscribe("libralien/+", (err) => {
        if (!err) {
            console.log("mqtt \"libralien/+\" connected");
        }
    });

    mqtt_client.subscribe("$SYS/broker/clients/active", (err) => {
        if (!err) {
            console.log("mqtt \"$SYS/broker/clients/connected\" connected");
        }
    });
});


mqtt_client.on("message", (topic, message) => {
    console.log(topic, message.toString());

    let pattern = /libralien\/[a-zA-Z0-9\-]+/;
    if (pattern.test(topic)) {
        let deviceUuid = topic.split("/")[1];
        if (deviceUuid == "presence") return;

        let sql = "SELECT devices.* FROM devices WHERE serial_number = ? LIMIT 1;"
        // let sql = "SELECT devices.id AS device_id, devices.*, device_metas.*, entities.* FROM devices JOIN device_metas ON device_metas.devices_id = devices.id JOIN entities ON entities.id = device_metas.entity_id WHERE serial_number = ? LIMIT 1;";
        let values = [deviceUuid];

        con.query(sql, values, function (err, deviceResult) {
            if (err) throw err;
            if (deviceResult.length == 0) return;
            const device_id = deviceResult[0].id;
            const device_type_id = deviceResult[0].device_type_id;
            const group_id = deviceResult[0].group_id;


            let sql = "SELECT * FROM groups WHERE id = ? LIMIT 1";
            let values = [group_id];
            con.query(sql, values, function (err, groupResult) {
                if (err) throw err;

                if (groupResult.length == 0) return;
                const groupUuid = groupResult[0].uuid;

                let mqtt_data = JSON.parse(message.toString());
                let UTCTime = new Date().toISOString();

                // console.log(mqtt_data, device_id)

                let sql = "SELECT * FROM entities WHERE name = ? AND device_type_id = ? LIMIT 1";
                let values = [mqtt_data.event, device_type_id];
                con.query(sql, values, function (err, entityResult) {
                    if (err) throw err;
                    if (entityResult.length == 0) return;
                    console.log(entityResult);

                    let entity_id = entityResult[0].id;
                    let options = entityResult[0].options;
                    let real_value = mqtt_data.value;
                    if (options !== null) {
                        options = JSON.parse(options);
                        real_value = options[mqtt_data.value];
                    }

                    let appletsql = "SELECT * FROM applet_nodes INNER JOIN applets ON applet_nodes.applet_id = applets.id WHERE applet_nodes.device_id = ? AND applet_nodes.type = ? AND applet_nodes.entity_id = ? AND applets.status = ?";
                    let appletvalues = [device_id, "trigger", entity_id, 1];
                    con.query(appletsql, appletvalues, function (err, appletResult) {
                        if (err) throw err;
                        if (appletResult.length == 0) return;
                        console.log("triggered");
                        console.log(appletResult);
                        appletResult.forEach(function (applet) {
                            let logic = false;
                            console.log(applet)
                            const applet_id = applet.applet_id
                            switch (applet.condition) {
                                case "==":
                                    logic = mqtt_data.value == applet.value;
                                    break;
                                case "!=":
                                    logic = mqtt_data.value != applet.value;
                                    break;
                                case ">":
                                    logic = mqtt_data.value > applet.value;
                                    break;
                                case "<":
                                    logic = mqtt_data.value < applet.value;
                                    break;
                                case ">=":
                                    logic = mqtt_data.value >= applet.value;
                                    break;
                                case "<=":
                                    logic = mqtt_data.value <= applet.value;
                                    break;
                            }
                            console.log("logic", logic, applet_id);
                            if (logic) {
                                console.log("triggered2");
                                let appletsql = "SELECT * FROM applet_nodes WHERE applet_id = ? AND type = ? LIMIT 1";
                                let appletvalues = [applet_id, "action"];
                                con.query(appletsql, appletvalues, function (err, appletActionResult) {
                                    if (err) throw err;
                                    if (appletActionResult.length == 0) return;
                                    let appletAction = appletActionResult[0];
                                    let appletActionDeviceID = appletAction.device_id;
                                    let actionEntityId = appletAction.entity_id;
                                    let actionEntityValue = appletAction.value;

                                    console.log("applet", appletActionDeviceID);

                                    let sql = "SELECT * FROM devices WHERE id = ? LIMIT 1;";
                                    let values = [appletActionDeviceID];


                                    con.query(sql, values, function (err, deviceResult) {
                                        if (err) throw err;
                                        if (deviceResult.length == 0) return;
                                        const device_id = deviceResult[0].id;
                                        const device_uuid = deviceResult[0].serial_number;

                                        console.log("device", device_uuid);

                                        let sql = "SELECT * FROM entities WHERE id = ? LIMIT 1";
                                        let values = [actionEntityId];
                                        con.query(sql, values, function (err, entityResult) {
                                            const entity_name = entityResult[0].name;
                                            let options = entityResult[0].options;

                                            let readable_value = actionEntityValue;
                                            if (options !== null) {
                                                options = JSON.parse(options);
                                                readable_value = options[actionEntityValue];
                                            }

                                            let eventData = {
                                                device: device_uuid,
                                                initiator: "applet",
                                                type: "event",
                                                event: entity_name,
                                                value: readable_value,
                                                time: moment(UTCTime).tz(groupResult[0].timezone).format("DD/MM/YYYY HH:mm:ss")
                                            };
                                            console.log(eventData);

                                            io.to(groupUuid).emit('event', eventData);

                                            let sqlEvent = "INSERT INTO `device_events` (`initiator`, `device_id`, `group_id`, `type`, `event`, `value`, `created_at`) VALUES (?, ?, ?, ?, ?, ?, ?)";
                                            let valuesEvent = ["applet", appletActionDeviceID, group_id, eventData.type, eventData.event, eventData.value, UTCTime];
                                            con.query(sqlEvent, valuesEvent, function (err, result) {
                                                if (err) throw err;
                                                console.log(result)

                                                let data = {
                                                    "type": "action",
                                                    "event": entity_name,
                                                    "value": actionEntityValue
                                                }

                                                console.log(data)

                                                mqtt_client.publish(`libralien/${device_uuid}/action`, JSON.stringify(data));
                                            });
                                        });



                                    });

                                });
                            }
                        });

                    });


                    let data = {
                        device: deviceUuid,
                        initiator: "device",
                        type: mqtt_data.type,
                        event: mqtt_data.event,
                        time: moment(UTCTime).tz(groupResult[0].timezone).format("DD/MM/YYYY HH:mm:ss"),
                        value: real_value
                    };

                    if (mqtt_data.type == "presence") {
                        let d = {
                            value: mqtt_data.value
                        }
                        if (mqtt_data.value == 1) {
                            d.value = "online";
                        } else {
                            d.value = "offline";
                        }
                        let sqlUpdateMeta = "UPDATE devices SET status = ? WHERE id = ?";
                        let valuesUpdateMeta = [d.value, device_id];
                        con.query(sqlUpdateMeta, valuesUpdateMeta, function (err, result) {
                            if (err) throw err;
                            // console.log(result);
                        });

                        let meta = {
                            device: deviceUuid,
                            meta: "status",
                            value: d.value
                        };

                        io.to(groupUuid).emit('meta', meta);
                    }

                    let sqlMeta = "SELECT * FROM device_metas JOIN entities ON device_metas.entity_id=entities.id WHERE device_metas.devices_id = ? AND entities.name = ? LIMIT 1";
                    let valuesMeta = [device_id, mqtt_data.event];
                    con.query(sqlMeta, valuesMeta, function (err, metaResult) {
                        if (err) throw err;
                        if (metaResult.length == 0) return;
                        // console.log(metaResult);
                        let meta = {
                            device: deviceUuid,
                            meta: mqtt_data.event,
                            // value: mqtt_data.value
                        };

                        let options = entityResult[0].options;
                        if (options !== null) {
                            options = JSON.parse(options);
                            meta.value = options[mqtt_data.value];
                        } else {
                            meta.value = mqtt_data.value;
                        }

                        let sqlUpdateMeta = "UPDATE device_metas SET value = ? WHERE id = ?";
                        let valuesUpdateMeta = [
                            mqtt_data.value,
                            metaResult[0].id
                        ];
                        con.query(sqlUpdateMeta, valuesUpdateMeta, function (err, result) {
                            if (err) throw err;
                            // console.log(result);
                        });
                        io.to(groupUuid).emit('meta', meta);
                    });

                    let sql = "INSERT INTO `device_events` (`initiator`, `device_id`, `group_id`, `type`, `event`, `value`, `created_at`) VALUES (?, ?, ?, ?, ?, ?, ?)";
                    let values = [data.initiator, device_id, group_id, data.type, data.event, mqtt_data.value, UTCTime];
                    con.query(sql, values, function (err, result) {
                        if (err) throw err;
                        // console.log(result);

                        io.to(groupUuid).emit('event', data);
                        console.log(data)
                    });
                });

            });
        });

        // mqtt_client.publish("libralien/presence", "check");
        // check if the id is in database


    }
});


app.get('/', (req, res) => {
    // res.send('<h1>Hello world</h1>');
    res.sendFile(__dirname + '/index.html');

});



server.listen(3000, () => {
    console.log('listening on *:3000');
});
