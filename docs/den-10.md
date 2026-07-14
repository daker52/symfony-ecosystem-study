# Den 10 — RabbitMQ + Messenger AMQP

**Datum:** 14. 7. 2026  
**Status:** hotovo (config + teorie + Docker compose; lokálně default pořád Doctrine transport)

Messenger umí vyměnit transport bez změny handlerů — to je point oproti „jen queue driver“ v Laravelu.

---

## Pojmy

| Pojem | Význam |
|-------|--------|
| **Broker** | RabbitMQ server |
| **Exchange** | kam producer posílá (routing) |
| **Queue** | fronta zpráv pro consumery |
| **Consumer** | `messenger:consume async` |

Flow: Message → Handler → Transport (doctrine / amqp / redis)

---

## Přepnutí na RabbitMQ

```powershell
docker compose -f docker-compose.rabbitmq.yml up -d
# UI: http://localhost:15672  guest / guest
```

```env
MESSENGER_TRANSPORT_DSN=amqp://guest:guest@127.0.0.1:5672/%2f/messages
```

Potřeba: `composer require symfony/amqp-messenger` + PHP `ext-amqp`.

Bez RabbitMQ zůstává `doctrine://default` — stejné message třídy, jiný transport.

---

## Laravel analogie

| Laravel | Symfony |
|---------|---------|
| `ShouldQueue` Job | Message + routing na `async` |
| `queue:work redis` | `messenger:consume async` |
| failed_jobs | `failed` transport + `messenger:failed:*` |

---

## Zdroje

- https://www.rabbitmq.com/tutorials
- https://symfony.com/doc/current/messenger.html#amqp-transport
