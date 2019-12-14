# Transient

This transient helper does not expire the transient. Instead, it has `expire_at` attribute, so you can decide reuse it, extend expiration, or update the value.

To create a new transient, use `new Transient( $items, $cache_ttl )`.

To create an exiting transient from key, use `Transient::get_transient( $key )`.

To store the instance into transient, use `set_transient( $key, json_decode( wp_json_encode( $transient ), true ) )`.

## Methods
### is_expired(): bool
