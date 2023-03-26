import EmailCheckItem from './types/EmailCheckItem';
import { check_email } from './utils';

require('dotenv').config({path: process.env.INIT_CWD});

const mariadb = require('mariadb');

(async () => {
    const conn = await mariadb.createConnection({
        host: process.env.MYSQL_HOST,
        port: process.env.MYSQL_PORT,
        user: process.env.MYSQL_USER,
        password: process.env.MYSQL_PASSWORD,
        database: process.env.MYSQL_DATABASE,
    });

    const command = `
        SELECT
            id,
            email
        FROM users_emails
        WHERE
            checked = 0
        LIMIT 10000
    `;

    const items = await conn.query(command);

    console.log(`Найдено пользователей ${items.length}`);

    await setTimeout(() => {}, 2e3);
    
    items.forEach((item: EmailCheckItem) => {
        check_email(item.email)
            .then((res: Number) => {
                const command = `UPDATE users_emails SET checked = 1, valid = ${res} WHERE id = ${item.id}`;
                conn.query(command).then(() => {
                    console.log(`Обработан email ${item.email}. Статус проверки - ${res}`);
                });
            });
    });

    await setTimeout(() => conn.end(), 70000);
})();