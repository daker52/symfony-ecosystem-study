# Den 15 — RabbitMQ napevno + Broker Passport

**Datum:** 19. 7. 2026  
**Status:** hotovo

Dnes ne jen „přepnu DSN“. Udělal jsem něco, co v běžných tutorialech není: **Broker Passport** — každá Pulse stage si pamatuje, *kudy* zpráva letěla (exchange → queue → routing key).

---

## Problém, který jsem řešil

Na Windows nemám vždy Docker / pecl `ext-amqp`. Oficiální `amqp://` transport by mě zablokoval.  
Laravelák by často řekl „bez Redis/Rabbit to nejde ukázat“. Já jsem chtěl **stejný mental model jako Rabbit**, i offline.

---

## Co je Broker Passport (wow)

1. **Vlastní Messenger transporty**
   - `amqpsim://` — topology twin (exchange / queue / binding) v SQLite, bez Dockeru
   - `amqplib://` — pure PHP klient (`php-amqplib`) na živý RabbitMQ
2. **Stamp `BrokerPassportStamp`** + middleware → handler zapíše hop do `WorkOrderEvent`
3. **Pulse timeline** ukáže badge `amqpsim` / `rabbitmq` + `pulse.work → pulse.async`
4. **`app:pulse:broker --export`** vypálí oficiální **RabbitMQ `definitions.json`** — topologii nejdřív navrhneš v DSN, pak importneš do brokera

Tohle je ten „zázrak“ oproti typickému junior demu: ne jen consume message, ale **pozorovatelná cesta zprávy** + export topologie.

---

## Jak spustit (teď — bez Dockeru)

```powershell
cd C:\Users\Hak\Projects\symfony-ecosystem-study\demo
# .env už má amqpsim://…

php bin/console app:pulse:broker --export
php bin/console messenger:consume async -vv
# druhý terminál: web server + /pulse
```

V timeline uvidíš u stage: **amqpsim · pulse.work → pulse.async**.

---

## Jak přepnout na živý Rabbit

```powershell
docker compose -f docker-compose.rabbitmq.yml up -d
```

V `.env`:
```env
MESSENGER_TRANSPORT_DSN=amqplib://guest:guest@127.0.0.1:5672/%2f?exchange=pulse.work&queue=pulse.async&routing_key=work.advance
```

Pak:
```powershell
php bin/console app:pulse:broker --export
# UI http://localhost:15672 → Import definitions (var/rabbit-definitions.json)
php bin/console messenger:consume async -vv
```

Handlery **se nemění** — jen transport. Badge v UI se přepne na `rabbitmq`.

---

## Pojmy, co umím říct

| Pojem | U mě |
|-------|------|
| Exchange | `pulse.work` (direct) |
| Queue | `pulse.async` |
| Routing key | `work.advance` |
| Consumer | `messenger:consume async` |
| Passport | audit hop u každé stage |

---

## Soubory

- `src/Messenger/Transport/AmqpSimTransport.php` (+ Factory)
- `src/Messenger/Transport/AmqpLibTransport.php` (+ Factory)
- `src/Messenger/Stamp/BrokerPassportStamp.php`
- `src/Messenger/Middleware/BrokerPassportMiddleware.php`
- `src/Command/PulseBrokerCommand.php`
- `src/Service/RabbitTopologyExporter.php`
- `var/rabbit-definitions.json` (generovaný)

---

## Wow věta pro zadavatele

> Messenger transport jsem nebral jako magickou DSN string. Udělal jsem Broker Passport — každá async stage v Pulse nese exchange/queue/routing key. Offline jedu topology twin (`amqpsim`), online `amqplib` na Rabbit, a `app:pulse:broker --export` mi z kódu vypálí Rabbit definitions.json. Handlery zůstávají stejné.

---

## Zdroje

- https://www.rabbitmq.com/tutorials
- https://www.rabbitmq.com/docs/definitions
- https://symfony.com/doc/current/messenger.html#amqp-transport
- https://github.com/php-amqplib/php-amqplib
