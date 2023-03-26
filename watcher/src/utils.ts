function getRandomNumber(min = 0, max = 100) {
    let difference = max - min;

    let rand = Math.random();

    rand = Math.floor(rand * difference);

    rand = rand + min;

    return rand;
}

export function createDate(days) {
    const result = new Date();

    result.setDate(result.getDate() + days)

    return Math.floor(result.getTime() / 1000);
}

export function send_email($email, $from, $to, $subj, $body) {
    const mail = {
        email: $email,
        from: $from,
        to: $to,
        subj: $subj,
        body: $body,
    };

    const timeout = getRandomNumber(1000, 10000);

    setTimeout(
        () => {
            console.log(`Отправлено письмо`, mail, `Отправлено за ${timeout / 1000} сек.`)
        },
        timeout
    );
}

export async function check_email($email): Promise<Number>
{
    const timeout = getRandomNumber(1000, 60000);

    return new Promise((resolve) => {
        setTimeout(() => {
            const result = Math.round(Math.random());

            console.log(`Сформирован результат по email ${$email}. За интервал ${timeout / 1000} сек.`);
            
            resolve(result);
        }, timeout);
    });
}