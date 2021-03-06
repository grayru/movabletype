<?php
# Movable Type (r) Open Source (C) 2001-2012 Six Apart, Ltd.
# This program is distributed under the terms of the
# GNU General Public License, version 2.
#
# $Id$

function smarty_block_mtauthors($args, $content, &$ctx, &$repeat) {
    $localvars = array('authors', 'author', 'authors_counter', 'blog_id');
    if (!isset($content)) {
        $ctx->localize($localvars);
        $args['blog_id'] = $ctx->stash('blog_id');

        if ( isset( $args['id'] ) ) {
            $args['author_id'] = $args['id'];
        } elseif ( isset( $args['username'] ) ) {
            $args['author_name'] = $args['username'];
        } elseif (isset($args['display_name'])) {
            $args['author_nickname'] = $args['display_name'];
        }

        if (isset($args['sort_by'])) {
            if ($args['sort_by'] == 'display_name') {
                $args['sort_by'] = 'nickname';
            }
            if ($args['sort_by'] != 'score' && $args['sort_by'] != 'rate') {
                $args['sort_by'] = 'author_'.$args['sort_by'];
            }
        } else {
            $args['sort_by'] = 'author_created_on';
        }
        if (!isset($args['status'])) {
            $args['status'] = 'enabled';
        }

        if (isset($args['scoring_to'])) {
            $args['_scoring_to_obj'] = $ctx->stash($args['scoring_to']);
            if (is_null($args['_scoring_to_obj'])) {
                $ctx->restore($localvars);
                $repeat = false;
                return;
            }
        }
        $authors = $ctx->mt->db()->fetch_authors($args);
        $ctx->stash('authors', $authors);
        $counter = 0;
    } else {
        $authors = $ctx->stash('authors');
        $counter = $ctx->stash('authors_counter');
    }
    if ($counter < count($authors)) {
        $author = $authors[$counter];
        $ctx->stash('author', $author);
        $ctx->stash('authors_counter', $counter + 1);
        $count = $counter + 1;
        $ctx->__stash['vars']['__counter__'] = $count;
        $ctx->__stash['vars']['__odd__'] = ($count % 2) == 1;
        $ctx->__stash['vars']['__even__'] = ($count % 2) == 0;
        $ctx->__stash['vars']['__first__'] = $count == 1;
        $ctx->__stash['vars']['__last__'] = ($count == count($authors));
        $repeat = true;
    } else {
        $ctx->restore($localvars);
        $repeat = false;
    }
    return $content;
}
?>
