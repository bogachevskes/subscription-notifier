import Subscription from './types/Subscription';
import { send_email, createDate } from './utils';

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

    const timestamp = createDate(3);

    const command = `
        SELECT
            DISTINCT u.username,
            ue.email,
            p.name as product_name
        FROM users_products up
        LEFT JOIN users u on up.user_id = u.id 
        LEFT JOIN users_emails ue on u.id = ue.user_id 
        LEFT JOIN products p on up.product_id = p.id
        WHERE
            ue.confirmed = 1
        AND
            ue.valid = 1
        AND
            up.validts <= ${timestamp}
    `;
   
    
    const items = await conn.query(command);

    conn.end();

    console.log(`Найдено пользователей ${items.length}. Дата ${timestamp}`);

    await setTimeout(() => {}, 2000);
    
    items.forEach((subscription: Subscription) => {
        send_email(
            subscription.email,
            'no-reply@karma8.io',
            subscription.email,
            'subscription expiration',
            `${subscription.username}, your subscription "${subscription.product_name}" is expiring soon`
        );
    });

})();